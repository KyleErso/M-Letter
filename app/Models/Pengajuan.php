<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    // Nama tabel kustom
    protected $table = 'pengajuans';

    // Primary key
    protected $primaryKey = 'id_pengajuan';

    public $incrementing = true;
    protected $keyType = 'int';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'mahasiswa_id',
        'tanggal_pengajuan',
        'status',
        'alasan_penolakan',
        'tanggal_persetujuan',
        'kaprodi_id',
        'tahun_ajaran_kode',
        'id_jenis_surat',
    ];

    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(\App\Models\Mahasiswa::class, 'mahasiswa_id', 'id');
    }
    

    // Relasi ke Kaprodi
    public function kaprodi()
    {
        return $this->belongsTo(User::class, 'kaprodi_id');
    }

    // Relasi ke Tahun Ajaran
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_kode', 'kode_tahun');
    }

    // ✅ Tambahkan Relasi ke Jenis Surat
    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class, 'id_jenis_surat');
    }
    public function surat_file()
{
    return $this->hasOne(SuratFile::class, 'pengajuan_id', 'id_pengajuan');
}

}
