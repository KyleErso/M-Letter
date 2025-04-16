<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    // Nama tabel yang digunakan
    protected $table = 'prodis';

    // Primary key adalah 'kode_prodi'
    protected $primaryKey = 'kode_prodi';

    // Karena primary key berupa string dan tidak auto-increment
    public $incrementing = false;
    protected $keyType = 'string';

    // Nonaktifkan timestamps (created_at & updated_at)
    public $timestamps = false;

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'kode_prodi',
        'nama_prodi',
        'jenjang',
        'kode_fakultas',
    ];

    /**
     * Relasi dengan model Fakultas.
     * Field 'kode_fakultas' pada tabel prodis mengacu ke 'kode_fakultas' di tabel fakultas.
     */
    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'kode_fakultas', 'kode_fakultas');
    }

    /**
     * Relasi dengan model Mahasiswa.
     * Field 'kode_prodi' di tabel mahasiswas mengacu ke primary key 'kode_prodi' di tabel prodis.
     */
    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'kode_prodi', 'kode_prodi');
    }
}
