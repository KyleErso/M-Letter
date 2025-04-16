<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPengajuanSuratPengantarMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'detail_pengajuan_surat_pengantar_mahasiswa';

    protected $fillable = [
        'pengajuan_id',
        'mahasiswa_id',
        'tujuan_surat',
        'nama_pt',
        'alamat_pt',
        'nama_mata_kuliah',
        'kode_mata_kuliah',
        'semester',
        'data_mahasiswa',
        'tujuan',
        'topik',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}
