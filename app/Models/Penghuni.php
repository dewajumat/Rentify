<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penghuni extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'penghuni';
    protected $primaryKey = 'penghuni_id';

    protected $fillable = [
        'penghuni_id',
        'foto',
        'name',
        'nik',
        'no_kk',
        'jenis_kelamin',
        'no_handphone',
        'email',
    ];
    public $timestamps = false;
}
