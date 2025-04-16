<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Support\Facades\DB;

class InstitutionController extends Controller
{
    /**
     * Tampilkan form untuk menambah Fakultas dan Prodi.
     */
    public function index()
    {
        // Mengambil semua data prodi beserta relasi fakultasnya
        $prodis = Prodi::with('fakultas')->get();
    
        return view('superadmin.institution', compact('prodis'));
    }
    

    public function create()
    {
        $prodis = Prodi::with('fakultas')->get();
        $fakultas = Fakultas::all();
        return view('superadmin.institution', compact('prodis', 'fakultas'));
    }
    


    /**
     * Proses penyimpanan data Fakultas dan Prodi.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            // Validasi data fakultas
            'kode_fakultas' => 'required|string|max:20|unique:fakultas,kode_fakultas',
            'nama_fakultas' => 'required|string|max:100',
            // Validasi data prodi
            'kode_prodi'    => 'required|string|max:20|unique:prodis,kode_prodi',
            'nama_prodi'    => 'required|string|max:100',
            'jenjang'       => 'required|string|max:3',
        ]);

        // Gunakan transaction untuk memastikan kedua data tersimpan bersama-sama
        DB::transaction(function () use ($validated) {
            // Simpan data Fakultas
            $fakultas = Fakultas::create([
                'kode_fakultas' => $validated['kode_fakultas'],
                'nama_fakultas' => $validated['nama_fakultas'],
            ]);

            // Simpan data Prodi dengan mengaitkan ke Fakultas yang baru disimpan
            Prodi::create([
                'kode_prodi'    => $validated['kode_prodi'],
                'nama_prodi'    => $validated['nama_prodi'],
                'jenjang'       => $validated['jenjang'],
                'kode_fakultas' => $fakultas->kode_fakultas,
            ]);
        });

        return redirect()->route('superadmin.dashboard')
                         ->with('success', 'Fakultas dan Prodi berhasil ditambahkan.');
    }

    public function storeProdi(Request $request)
{
    $validated = $request->validate([
        'kode_fakultas' => 'required|exists:fakultas,kode_fakultas',
        'kode_prodi'    => 'required|string|max:20|unique:prodis,kode_prodi',
        'nama_prodi'    => 'required|string|max:100',
        'jenjang'       => 'required|string|max:3',
    ]);

    // Simpan data Prodi saja
    Prodi::create($validated);

    return redirect()->route('superadmin.institution.create')
                     ->with('success', 'Prodi berhasil ditambahkan.');
}

public function destroyProdi($kode_prodi)
{
    // Temukan Prodi berdasarkan kode_prodi, jika tidak ditemukan akan menghasilkan error 404
    $prodi = Prodi::findOrFail($kode_prodi);

    // Hapus data prodi tersebut
    $prodi->delete();

    // Redirect kembali ke halaman create dengan pesan sukses
    return redirect()->route('superadmin.institution.create')
                     ->with('success', 'Prodi berhasil dihapus.');
}


}
