<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran';
    protected $primaryKey = 'kode_tahun';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_tahun', 'semester', 'status'
    ];
}
