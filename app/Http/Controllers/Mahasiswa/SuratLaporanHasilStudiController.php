<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pengajuan;
use App\Models\TahunAjaran;

class SuratLaporanHasilStudiController extends Controller
{
    /**
     * Menampilkan form pengajuan Laporan Hasil Studi.
     */
    public function form()
    {
        return view('mahasiswa.form.laporanhasilstudi');
    }

    /**
     * Menyimpan data pengajuan Laporan Hasil Studi.
     */
    public function store(Request $request)
    {
        // Validasi input jika diperlukan
        // $request->validate([
        //     'keperluan_lhs' => 'required|string|max:255',
        // ]);

        // Ambil tahun ajaran aktif
        $activeTahunAjaran = TahunAjaran::where('status', 'aktif')->first();
        if (!$activeTahunAjaran) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        // Buat data pengajuan
        $pengajuan = new Pengajuan();
        $pengajuan->mahasiswa_id      = Auth::id();
        $pengajuan->tanggal_pengajuan = now();
        $pengajuan->id_jenis_surat    = 4; // Asumsikan 3 mewakili Laporan Hasil Studi
        $pengajuan->tahun_ajaran_kode = $activeTahunAjaran->kode_tahun;
        $pengajuan->save();

        // Simpan data detail pengajuan ke tabel detail_pengajuan_surat_laporan_hasil_studi
        DB::table('detail_pengajuan_surat_laporan_hasil_studi')->insert([
            'pengajuan_id' => $pengajuan->getKey(),
            'mahasiswa_id' => Auth::id(),
            'keperluan_lhs' => $request->input('keperluan_lhs'),
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->route('mahasiswa.dashboard')
                         ->with('success', 'Pengajuan Laporan Hasil Studi berhasil diajukan.');
    }
}
