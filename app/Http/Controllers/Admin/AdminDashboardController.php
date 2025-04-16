<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\SuratFile;
use Illuminate\Support\Facades\Storage;


class AdminDashboardController extends Controller
{
    /**
     * Menampilkan daftar surat yang disetujui beserta relasinya.
     */
    public function index()
    {
        // Ambil data admin beserta relasinya.
        $adminData = auth()->user()->admin()->with('prodi')->first();
        
        // Pastikan admin memiliki data prodi
        if (!$adminData || !$adminData->prodi) {
            abort(403, 'Data prodi tidak ditemukan untuk admin ini.');
        }
        
        // Pengajuan yang belum diupload, filter berdasarkan kode_prodi admin
        $notUploaded = Pengajuan::with([
                'jenisSurat',
                'mahasiswa.prodi.fakultas',
                'surat_file'
            ])
            ->where('status', 'disetujui')
            ->whereDoesntHave('surat_file')
            ->whereHas('mahasiswa', function ($query) use ($adminData) {
                $query->where('kode_prodi', $adminData->prodi->kode_prodi);
            })
            ->orderBy('tanggal_pengajuan', 'desc')
            ->paginate(5, ['*'], 'notUploadedPage');

        // Pengajuan yang sudah diupload, filter berdasarkan kode_prodi admin
        $uploaded = Pengajuan::with([
                'jenisSurat',
                'mahasiswa.prodi.fakultas',
                'surat_file'
            ])
            ->where('status', 'disetujui')
            ->whereHas('surat_file')
            ->whereHas('mahasiswa', function ($query) use ($adminData) {
                $query->where('kode_prodi', $adminData->prodi->kode_prodi);
            })
            ->orderBy('tanggal_pengajuan', 'desc')
            ->paginate(5, ['*'], 'uploadedPage');

        // Ambil data admin beserta relasi prodi dan fakultas
        $admin = auth()->user()->admin()->with('prodi.fakultas')->first();

        return view('admin.dashboard', compact('notUploaded', 'uploaded', 'admin'));
    }


    /**
     * Mengunggah file surat untuk pengajuan tertentu.
     */
    public function uploadSurat(Request $request, $id)
    {
        $validatedData = $request->validate([
            'file_surat' => 'required|file|mimes:pdf,jpg,jpeg,png',
            'no_surat' => 'required|string|max:255',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);

        if ($request->hasFile('file_surat')) {
            // Hapus file surat yang lama jika ada
            if ($pengajuan->surat_file) {
                if (Storage::exists($pengajuan->surat_file->file_path)) {
                    Storage::delete($pengajuan->surat_file->file_path);
                }
                $pengajuan->surat_file->delete();
            }

            $file = $request->file('file_surat');
            $originalFilename = $file->getClientOriginalName();
            $sanitizedFilename = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $originalFilename);
            $uniqueFilename = $sanitizedFilename;

            // Simpan ke storage/app/public/surat_files dan bisa diakses lewat URL publik
            $path = $file->storeAs('surat_files', $uniqueFilename, 'public');


            SuratFile::create([
                'no_surat' => $validatedData['no_surat'],
                'pengajuan_id' => $pengajuan->id_pengajuan,
                'file_path' => $path, // Contoh: "public/surat_files/1617928450_nama_file.pdf"
            ]);
        }

        return redirect()->back()->with('success', 'File surat berhasil diupload.');
    }

    public function downloadSurat($id)
    {
        $pengajuan = Pengajuan::with('surat_file')->findOrFail($id);
    
        if (!$pengajuan->surat_file) {
            return redirect()->back()->with('error', 'Surat tidak ditemukan.');
        }
    
        $filePath = $pengajuan->surat_file->file_path;
        $originalName = $pengajuan->surat_file->original_filename;

        if (Storage::exists($filePath)) {
            return Storage::download($filePath, $originalName); // <-- Ini penting
        }
    
        return redirect()->back()->with('error', 'File surat tidak tersedia.');
    }
    
}
