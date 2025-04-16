<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratFile extends Model
{
    // Nama tabel, jika tidak sesuai konvensi 'surat_files'
    protected $table = 'surat_files';

    // Kolom yang bisa diisi secara massal
    protected $fillable = [
        'no_surat',
        'pengajuan_id',
        'file_path'
    ];

    /**
     * Relasi dengan pengajuan surat.
     */
    public function pengajuan()
    {
        return $this->belongsTo(\App\Models\Pengajuan::class, 'pengajuan_id', 'id_pengajuan');
    }

    public function getFullUrlAttribute()
{
    return asset('storage/' . $this->file_path);
}

}
