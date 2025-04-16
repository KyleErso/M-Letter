<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPengajuanSuratKeteranganMahasiswaAktif extends Model
{
    use HasFactory;

    protected $table = 'detail_pengajuan_surat_keterangan_mahasiswa_aktif';

    protected $fillable = [
        'pengajuan_id',
        'mahasiswa_id',
        'semester',
        'alamat',
        'keperluan_pengajuan',
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
