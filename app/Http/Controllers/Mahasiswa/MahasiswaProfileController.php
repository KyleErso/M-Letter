<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mahasiswa;
use App\Models\TahunAjaran;

class MahasiswaProfileController extends Controller
{
    // Menampilkan halaman profil mahasiswa
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::find($user->id);
        // Ambil data tahun ajaran aktif
        $activeTahunAjaran = TahunAjaran::where('status', 'aktif')->first();
        return view('mahasiswa.profile', compact('user', 'mahasiswa', 'activeTahunAjaran'));
    }

    // Hanya mengubah data alamat mahasiswa
    public function update(Request $request)
    {
        $request->validate([
            'alamat'  => 'nullable|string',
        ]);
    
        $user = Auth::user();
    
        Mahasiswa::updateOrCreate(
            ['id' => $user->id],
            ['id' => $user->id, 'alamat' => $request->input('alamat')]
        );
    
        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
    
}
