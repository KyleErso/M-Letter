<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\Kaprodi;
use Illuminate\Support\Facades\DB;

class KaprodiDashboard extends Controller
{
    /**
     * Menampilkan daftar pengajuan surat yang perlu diproses kaprodi.
     */
    public function index()
    {
        // Ambil data kaprodi berdasarkan ID user yang login
        $kaprodi = Kaprodi::where('id', auth()->user()->id)->first();
        $kaprodiKodeProdi = $kaprodi ? $kaprodi->kode_prodi : null;

        // Jika kode prodi tetap null, berarti data kaprodi tidak ditemukan atau belum diisi.
        if (!$kaprodiKodeProdi) {
            return redirect()->back()->with('error', 'Data prodi kaprodi tidak ditemukan.');
        }

        // Pengajuan pending dengan filter mahasiswa yang memiliki kode_prodi sama
        $pendingPengajuans = Pengajuan::with(['mahasiswa', 'jenisSurat', 'tahunAjaran'])
            ->where('status', 'menunggu')
            ->whereHas('mahasiswa', function ($query) use ($kaprodiKodeProdi) {
                $query->where('kode_prodi', $kaprodiKodeProdi);
            })
            ->orderBy('tanggal_pengajuan', 'desc')
            ->paginate(5, ['*'], 'pendingPage');

        // Pengajuan disetujui
        $approvedPengajuans = Pengajuan::with(['mahasiswa', 'jenisSurat', 'tahunAjaran'])
            ->where('status', 'disetujui')
            ->whereHas('mahasiswa', function ($query) use ($kaprodiKodeProdi) {
                $query->where('kode_prodi', $kaprodiKodeProdi);
            })
            ->orderBy('tanggal_pengajuan', 'desc')
            ->paginate(5, ['*'], 'approvedPage');

        // Pengajuan ditolak
        $rejectedPengajuans = Pengajuan::with(['mahasiswa', 'jenisSurat', 'tahunAjaran'])
            ->where('status', 'ditolak')
            ->whereHas('mahasiswa', function ($query) use ($kaprodiKodeProdi) {
                $query->where('kode_prodi', $kaprodiKodeProdi);
            })
            ->orderBy('tanggal_pengajuan', 'desc')
            ->paginate(5, ['*'], 'rejectedPage');

        // Tambahkan detail untuk setiap pengajuan berdasarkan jenis surat.
        // Iterasi pada masing-masing paginator (hampir sama seperti iterasi pada koleksi)
        foreach ($pendingPengajuans as $pengajuan) {
            $pengajuan->detail = $this->getDetailByJenisSurat($pengajuan);
        }
        foreach ($approvedPengajuans as $pengajuan) {
            $pengajuan->detail = $this->getDetailByJenisSurat($pengajuan);
        }
        foreach ($rejectedPengajuans as $pengajuan) {
            $pengajuan->detail = $this->getDetailByJenisSurat($pengajuan);
        }

        return view('kaprodi.dashboard', compact('pendingPengajuans', 'approvedPengajuans', 'rejectedPengajuans', 'kaprodi'));
    }

    /**
     * Fungsi helper untuk mengambil detail pengajuan berdasarkan jenis surat.
     *
     * @param Pengajuan $pengajuan
     * @return object|null
     */
    private function getDetailByJenisSurat($pengajuan)
    {
        $detail = null;
        switch ($pengajuan->id_jenis_surat) {
            case 1:
                $detail = DB::table('detail_pengajuan_surat_keterangan_mahasiswa_aktif')
                    ->where('pengajuan_id', $pengajuan->id_pengajuan)
                    ->first();
                break;
            case 2:
                $detail = DB::table('detail_pengajuan_surat_pengantar_mahasiswa')
                    ->where('pengajuan_id', $pengajuan->id_pengajuan)
                    ->first();
                break;
            case 3:
                $detail = DB::table('detail_pengajuan_surat_rekomendasi_mbkm')
                    ->where('pengajuan_id', $pengajuan->id_pengajuan)
                    ->first();
                break;
            case 4:
                $detail = DB::table('detail_pengajuan_surat_laporan_hasil_studi')
                    ->where('pengajuan_id', $pengajuan->id_pengajuan)
                    ->first();
                break;
            case 5:
                $detail = DB::table('detail_pengajuan_surat_kelulusan')
                    ->where('pengajuan_id', $pengajuan->id_pengajuan)
                    ->first();
                break;
            default:
                $detail = null;
                break;
        }
        return $detail;
    }

    /**
     * Menyetujui pengajuan surat.
     *
     * @param int $id
     */
    public function approve($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->status = 'disetujui';
        $pengajuan->kaprodi_id = auth()->id();
        $pengajuan->tanggal_persetujuan = now();
        $pengajuan->save();

        return redirect()->route('kaprodi.dashboard')
                         ->with('success', 'Pengajuan berhasil disetujui.');
    }

    /**
     * Menolak pengajuan surat dengan alasan penolakan.
     *
     * @param Request $request
     * @param int $id
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->status = 'ditolak';
        $pengajuan->kaprodi_id = auth()->id();
        $pengajuan->alasan_penolakan = $request->input('alasan_penolakan');
        $pengajuan->save();

        return redirect()->route('kaprodi.dashboard')
                         ->with('success', 'Pengajuan berhasil ditolak.');
    }
}
