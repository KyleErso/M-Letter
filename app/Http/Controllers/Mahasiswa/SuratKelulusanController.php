<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pengajuan;
use App\Models\TahunAjaran;

class SuratKelulusanController extends Controller
{
    /**
     * Menampilkan form pengajuan Surat Kelulusan.
     */
    public function form()
    {
        return view('mahasiswa.form.suratkelulusan');
    }

    /**
     * Menyimpan data pengajuan Surat Kelulusan.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'tanggal_kelulusan' => 'required|date',
        ]);

        // Ambil tahun ajaran yang aktif
        $activeTahunAjaran = TahunAjaran::where('status', 'aktif')->first();
        if (!$activeTahunAjaran) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        // Membuat data pengajuan
        $pengajuan = new Pengajuan();
        $pengajuan->mahasiswa_id      = Auth::id();
        $pengajuan->tanggal_pengajuan = now();
        $pengajuan->id_jenis_surat    = 5; // Nilai 5 mewakili Surat Kelulusan
        $pengajuan->tahun_ajaran_kode = $activeTahunAjaran->kode_tahun;
        $pengajuan->save();

        // Menyimpan data detail pengajuan ke tabel detail_pengajuan_surat_kelulusan
        DB::table('detail_pengajuan_surat_kelulusan')->insert([
            'pengajuan_id'      => $pengajuan->getKey(),
            'mahasiswa_id'      => Auth::id(),
            'tanggal_kelulusan' => $request->input('tanggal_kelulusan'),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        return redirect()->route('mahasiswa.dashboard')
                         ->with('success', 'Pengajuan Surat Kelulusan berhasil diajukan.');
    }
}
