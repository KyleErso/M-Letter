<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pengajuan;
use App\Models\TahunAjaran;

class SuratPengantarTugasController extends Controller
{
    /**
     * Menampilkan form pengajuan Surat Pengantar Tugas.
     */
    public function form()
    {
        return view('mahasiswa.form.suratpengantartugas');
    }

    /**
     * Menyimpan data pengajuan Surat Pengantar Tugas.
     */
    public function store(Request $request)
    {
        // Jika perlu, tambahkan validasi input, misalnya:
        // $request->validate([
        //     'tujuan_surat'      => 'required|string|max:255',
        //     'nama_pt'           => 'nullable|string|max:255',
        //     'alamat_pt'         => 'nullable|string|max:255',
        //     'nama_mata_kuliah'  => 'nullable|string|max:255',
        //     'kode_mata_kuliah'  => 'nullable|string|max:50',
        //     'semester'          => 'nullable|string|max:50',
        //     'data_mahasiswa'    => 'nullable|string',
        //     'tujuan'            => 'nullable|string|max:255',
        //     'topik'             => 'nullable|string|max:255',
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
        $pengajuan->id_jenis_surat    = 2; // Asumsikan 2 mewakili Surat Pengantar Tugas
        $pengajuan->tahun_ajaran_kode = $activeTahunAjaran->kode_tahun;
        $pengajuan->save();

        // Simpan data detail pengajuan ke tabel detail_pengajuan_surat_pengantar_mahasiswa
        DB::table('detail_pengajuan_surat_pengantar_mahasiswa')->insert([
            'pengajuan_id'     => $pengajuan->getKey(),
            'mahasiswa_id'     => Auth::id(),
            'tujuan_surat'     => $request->input('tujuan_surat'),
            'nama_pt'          => $request->input('nama_pt'),
            'alamat_pt'        => $request->input('alamat_pt'),
            'nama_mata_kuliah' => $request->input('nama_mata_kuliah'),
            'kode_mata_kuliah' => $request->input('kode_mata_kuliah'),
            'semester'         => $request->input('semester'),
            'data_mahasiswa'   => $request->input('data_mahasiswa'),
            'tujuan'           => $request->input('tujuan'),
            'topik'            => $request->input('topik'),
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        return redirect()->route('mahasiswa.dashboard')
                         ->with('success', 'Pengajuan Surat Pengantar Tugas berhasil diajukan.');
    }
}
