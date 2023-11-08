<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Properti extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'admin_id',
        'jenis_properti',
        'jumlah_bilik',
        'alamat',
        'harga_perbulan',
        'harga_per6bulan',
        'harga_pertahun',
    ];

    public $timestamps = false;
    protected $table = 'properti';
    protected $primaryKey = 'properti_id';

    public function bilik()
    {
        return $this->hasMany(Bilik::class, 'properti_id');
    }
}
