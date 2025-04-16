<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Mahasiswa;
use App\Models\Admin;
use App\Models\Kaprodi;

class User extends Authenticatable
{
    use SoftDeletes;
    use HasFactory, Notifiable;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';

    /**
     * Field yang dapat diisi secara massal.
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Field yang disembunyikan untuk serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast field sesuai tipe datanya.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi ke tabel mahasiswas.
     */
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'id', 'id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    /**
     * Relasi ke tabel admins.
     */
    public function admin()
    {
        return $this->hasOne(Admin::class, 'id', 'id');
    }

    /**
     * Relasi ke tabel kaprodis.
     */
    public function kaprodi()
    {
        return $this->hasOne(Kaprodi::class, 'id', 'id');
    }

    /**
     * Accessor untuk mendapatkan data fakultas.
     * Untuk admin dan kaprodi, diambil dari relasi masing-masing.
     * Untuk mahasiswa, diambil dari relasi mahasiswa->prodi->fakultas.
     */
    public function getFakultasAttribute()
    {
        if ($this->role === 'admin' && $this->relationLoaded('admin') && $this->admin) {
            return $this->admin->fakultas;
        } elseif ($this->role === 'kaprodi' && $this->relationLoaded('kaprodi') && $this->kaprodi) {
            return $this->kaprodi->fakultas;
        } elseif ($this->role === 'mahasiswa' && $this->relationLoaded('mahasiswa') && $this->mahasiswa) {
            // Pastikan relasi mahasiswa sudah di-load beserta prodi dan fakultas
            if ($this->mahasiswa->relationLoaded('prodi') && $this->mahasiswa->prodi) {
                return $this->mahasiswa->prodi->fakultas;
            }
        }
        return null;
    }

    /**
     * Accessor untuk mendapatkan data prodi.
     * Untuk admin dan kaprodi, diambil dari relasi masing-masing.
     * Untuk mahasiswa, diambil dari relasi mahasiswa->prodi.
     */
    public function getProdiAttribute()
    {
        if ($this->role === 'admin' && $this->relationLoaded('admin') && $this->admin) {
            return $this->admin->prodi;
        } elseif ($this->role === 'kaprodi' && $this->relationLoaded('kaprodi') && $this->kaprodi) {
            return $this->kaprodi->prodi;
        } elseif ($this->role === 'mahasiswa' && $this->relationLoaded('mahasiswa') && $this->mahasiswa) {
            if ($this->mahasiswa->relationLoaded('prodi') && $this->mahasiswa->prodi) {
                return $this->mahasiswa->prodi;
            }
        }
        return null;
    }
}
