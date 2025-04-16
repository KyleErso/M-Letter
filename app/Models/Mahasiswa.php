<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    // Nama tabel yang digunakan
    protected $table = 'mahasiswas';

    // Primary key pada tabel
    protected $primaryKey = 'id';

    // Karena 'id' tidak auto-increment (diambil dari tabel users)
    public $incrementing = false;

    // Tipe primary key
    protected $keyType = 'int';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'id',
        'kode_prodi',
        'tahun_ajaran_kode',
        'alamat'
    ];

    /**
     * Relasi dengan model User (satu-satu).
     * Field 'id' pada tabel mahasiswas merupakan foreign key yang mengacu ke 'id' di tabel users.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    /**
     * Relasi dengan model Prodi.
     * Field 'kode_prodi' pada tabel mahasiswas mengacu ke 'kode_prodi' di tabel prodis.
     */
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'kode_prodi', 'kode_prodi');
    }

    /**
     * Relasi dengan model TahunAjaran.
     * Field 'tahun_ajaran_kode' pada tabel mahasiswas mengacu ke 'kode_tahun' di tabel tahun_ajaran.
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_kode', 'kode_tahun');
    }
}
