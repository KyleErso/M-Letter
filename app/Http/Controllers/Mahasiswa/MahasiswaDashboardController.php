<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class MahasiswaDashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        // Ambil pengajuan mahasiswa beserta relasi yang diperlukan
        $pengajuan = Pengajuan::with([
            'mahasiswa',
            'kaprodi',
            'tahunAjaran',
            'jenisSurat',
            'surat_file'
        ])
            ->where('mahasiswa_id', $userId)
            ->get();

        // Ambil data tahun ajaran aktif
        $activeTahunAjaran = TahunAjaran::where('status', 'aktif')->first();

        // Ambil data mahasiswa beserta relasi prodi dan fakultas
        $mahasiswa = auth()->user()->mahasiswa()->with('prodi.fakultas')->first();

        // Untuk setiap pengajuan, ambil detail berdasarkan jenis surat
        foreach ($pengajuan as $p) {
            switch ($p->id_jenis_surat) {
                case 1:
                    $p->detail = DB::table('detail_pengajuan_surat_keterangan_mahasiswa_aktif')
                        ->where('pengajuan_id', $p->id_pengajuan)
                        ->first();
                    break;
                case 2:
                    $p->detail = DB::table('detail_pengajuan_surat_pengantar_mahasiswa')
                        ->where('pengajuan_id', $p->id_pengajuan)
                        ->first();
                    break;
                case 3:
                    $p->detail = DB::table('detail_pengajuan_surat_rekomendasi_mbkm')
                        ->where('pengajuan_id', $p->id_pengajuan)
                        ->first();
                    break;
                case 4:
                    $p->detail = DB::table('detail_pengajuan_surat_laporan_hasil_studi')
                        ->where('pengajuan_id', $p->id_pengajuan)
                        ->first();
                    break;
                case 5:
                    $p->detail = DB::table('detail_pengajuan_surat_kelulusan')
                        ->where('pengajuan_id', $p->id_pengajuan)
                        ->first();
                    break;
                default:
                    $p->detail = null;
                    break;
            }
        }
        
        return view('mahasiswa.dashboard', compact('pengajuan', 'activeTahunAjaran', 'mahasiswa'));
    }

    public function pengajuan()
    {
        $userId = auth()->id();
        
        // Ambil pengajuan mahasiswa beserta relasi yang diperlukan
        $pengajuan = Pengajuan::with([
            'mahasiswa',
            'kaprodi',
            'tahunAjaran',
            'jenisSurat',
            'surat_file'
        ])
        ->where('mahasiswa_id', $userId)
        ->get();
    
        // Ambil data tahun ajaran aktif
        $activeTahunAjaran = TahunAjaran::where('status', 'aktif')->first();
    
        // Ambil data mahasiswa beserta relasi prodi dan fakultas
        $mahasiswa = auth()->user()->mahasiswa()->with('prodi.fakultas')->first();
    
        // Untuk setiap pengajuan, ambil detail berdasarkan jenis surat
        foreach ($pengajuan as $p) {
            switch ($p->id_jenis_surat) {
                case 1:
                    $p->detail = DB::table('detail_pengajuan_surat_keterangan_mahasiswa_aktif')
                        ->where('pengajuan_id', $p->id_pengajuan)
                        ->first();
                    break;
                case 2:
                    $p->detail = DB::table('detail_pengajuan_surat_pengantar_mahasiswa')
                        ->where('pengajuan_id', $p->id_pengajuan)
                        ->first();
                    break;
                case 3:
                    $p->detail = DB::table('detail_pengajuan_surat_rekomendasi_mbkm')
                        ->where('pengajuan_id', $p->id_pengajuan)
                        ->first();
                    break;
                case 4:
                    $p->detail = DB::table('detail_pengajuan_surat_laporan_hasil_studi')
                        ->where('pengajuan_id', $p->id_pengajuan)
                        ->first();
                    break;
                case 5:
                    $p->detail = DB::table('detail_pengajuan_surat_kelulusan')
                        ->where('pengajuan_id', $p->id_pengajuan)
                        ->first();
                    break;
                default:
                    $p->detail = null;
                    break;
            }
        }
        
        // Kembalikan view khusus untuk pengajuan
        return view('mahasiswa.pengajuan', compact('pengajuan', 'activeTahunAjaran', 'mahasiswa'));
    }
    

    public function redirectPengajuan(Request $request)
    {
        $jenisSurat = $request->input('jenis_surat');
        $userId = auth()->id();

        if (empty($jenisSurat)) {
            return redirect()->back()->with('error', 'Pilih jenis surat yang akan diajukan.');
        }

        // Cek apakah sudah ada pengajuan dengan status 'menunggu' untuk jenis surat yang sama
        $existingPengajuan = Pengajuan::where('mahasiswa_id', $userId)
            ->where('status', 'menunggu')
            ->where('id_jenis_surat', $jenisSurat)
            ->exists();

        if ($existingPengajuan) {
            return redirect()->back()->with('error', 'Anda sudah memiliki pengajuan dengan jenis surat yang sama dan status menunggu.');
        }

        $routes = [
            '1' => [SuratKeteranganAktifController::class, 'form'],
            '2' => [SuratPengantarTugasController::class, 'form'],
            '4' => [SuratLaporanHasilStudiController::class, 'form'],
            '5' => [SuratKelulusanController::class, 'form'],
        ];

        if (isset($routes[$jenisSurat])) {
            return redirect()->action($routes[$jenisSurat]);
        }

        return redirect()->back()->with('error', 'Jenis surat tidak valid.');
    }

    public function downloadSurat($id)
    {
        $pengajuan = Pengajuan::with('surat_file')->findOrFail($id);

        if (!$pengajuan->surat_file) {
            return redirect()->back()->with('error', 'Surat tidak ditemukan.');
        }

        // Ambil path dan nama file
        $filePath = $pengajuan->surat_file->file_path;
        $originalName = $pengajuan->surat_file->original_filename ?? 'surat.pdf';

        // Hapus prefix '/storage/' jika ada
        $storagePath = str_replace('storage/', 'public/', $filePath);

        if (Storage::exists($storagePath)) {
            return Storage::download($storagePath, $originalName);
        }

        return redirect()->back()->with('error', 'File surat tidak tersedia.');
    }
}
