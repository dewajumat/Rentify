<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeliharaan extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'pemeliharaan';
    protected $primaryKey = 'pemeliharaan_id';

    protected $fillable = [
        'bilik_id',
        'tgl_pengajuan',
        'judul',
        'deskripsi',
        'gambar',
        'tgl_selesai',
        'total',
        'status_pemeliharaan',
        'ket_penolakan',
    ];
    public $timestamps = false;
}
