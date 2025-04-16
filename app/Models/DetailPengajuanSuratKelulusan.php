<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPengajuanSuratKelulusan extends Model
{
    use HasFactory;

    protected $table = 'detail_pengajuan_surat_kelulusan';

    protected $fillable = [
        'pengajuan_id',
        'mahasiswa_id',
        'tanggal_kelulusan',
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
