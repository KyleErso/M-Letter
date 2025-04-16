<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input sesuai kebutuhan Anda
        $request->validate([
            // contoh validasi, sesuaikan dengan field lain
            'id_jenis_surat' => 'required',
            // Field-field lainnya...
        ]);

        // Ambil data tahun ajaran dengan status aktif
        $activeTahunAjaran = TahunAjaran::where('status', 'aktif')->first();

        if (!$activeTahunAjaran) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        // Siapkan data untuk disimpan
        $data = $request->all();
        // Set nilai tahun ajaran dari data aktif yang ditemukan
        $data['tahun_ajaran_kode'] = $activeTahunAjaran->kode_tahun;

        // Simpan data pengajuan
        Pengajuan::create($data);

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengajuan $pengajuan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengajuan $pengajuan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengajuan $pengajuan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengajuan $pengajuan)
    {
        //
    }
}
