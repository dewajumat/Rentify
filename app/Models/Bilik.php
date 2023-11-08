<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bilik extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'bilik';
    protected $primaryKey = 'bilik_id';

    protected $fillable = [
        'penghuni_id',
        'properti_id',
        'no_bilik',
        'isFilled',
        'status_hunian',
        'tipe_hunian',
        'status_pembayaran',
        'total_pembayaran',
        'ket_pembatalan',
    ];
    public $timestamps = false;

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'bilik_id');
    }

    public function properti()
    {
        return $this->belongsTo(Properti::class, 'properti_id');
    }
}
