<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pengajuan;
use App\Models\TahunAjaran;
use App\Models\Mahasiswa;

class SuratKeteranganAktifController extends Controller
{
    /**
     * Menampilkan form pengajuan Surat Keterangan Aktif.
     */
    public function form()
    {
        return view('mahasiswa.form.suratketeranganaktif');
    }

    /**
     * Menyimpan data pengajuan Surat Keterangan Aktif.
     */
    public function store(Request $request)
    {
        // Validasi input jika diperlukan, misalnya:
        // $request->validate([
        //     'semester'             => 'required|string|max:50',
        //     'keperluan_pengajuan'  => 'nullable|string|max:255',
        // ]);

        // Ambil tahun ajaran aktif
        $activeTahunAjaran = TahunAjaran::where('status', 'aktif')->first();
        if (!$activeTahunAjaran) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        // Ambil data mahasiswa berdasarkan user yang sedang login
        $mahasiswa = Mahasiswa::find(Auth::id());
        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Buat data pengajuan
        $pengajuan = new Pengajuan();
        $pengajuan->mahasiswa_id      = Auth::id();
        $pengajuan->tanggal_pengajuan = now();
        $pengajuan->id_jenis_surat    = 1; // Asumsikan 1 mewakili Surat Keterangan Aktif
        $pengajuan->tahun_ajaran_kode = $activeTahunAjaran->kode_tahun;
        $pengajuan->save();

        // Simpan data detail pengajuan ke tabel detail_pengajuan_surat_keterangan_mahasiswa_aktif
        DB::table('detail_pengajuan_surat_keterangan_mahasiswa_aktif')->insert([
            'pengajuan_id'        => $pengajuan->getKey(),
            'mahasiswa_id'        => Auth::id(),
            'semester'            => $request->input('semester'),
            'alamat'              => $mahasiswa->alamat, // ambil alamat dari model Mahasiswa
            'keperluan_pengajuan' => $request->input('keperluan_pengajuan'),
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        return redirect()->route('mahasiswa.dashboard')
                         ->with('success', 'Pengajuan Surat Keterangan Aktif berhasil diajukan.');
    }
}
