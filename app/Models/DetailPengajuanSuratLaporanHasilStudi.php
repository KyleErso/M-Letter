<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPengajuanSuratLaporanHasilStudi extends Model
{
    use HasFactory;

    protected $table = 'detail_pengajuan_surat_laporan_hasil_studi';

    protected $fillable = [
        'pengajuan_id',
        'mahasiswa_id',
        'keperluan_lhs',
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
