<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    // Nama tabel yang digunakan
    protected $table = 'fakultas';

    // Primary key adalah 'kode_fakultas'
    protected $primaryKey = 'kode_fakultas';

    // Karena primary key berupa string dan tidak auto-increment
    public $incrementing = false;
    protected $keyType = 'string';

    // Nonaktifkan timestamps (created_at & updated_at)
    public $timestamps = false;

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'kode_fakultas',
        'nama_fakultas',
    ];

    /**
     * Relasi dengan model Prodi.
     * Satu fakultas dapat memiliki banyak prodi.
     */
    public function prodis()
    {
        return $this->hasMany(Prodi::class, 'kode_fakultas', 'kode_fakultas');
    }
}
