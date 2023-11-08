<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'pembayaran';
    protected $primaryKey = 'pembayaran_id';

    protected $fillable = [
        'bilik_id',
        'penghuni_id',
        'bulan_start_terbayar',
        'bulan_end_terbayar',
        'tgl_pembayaran',
        'bukti_pembayaran',
        'stat_pembayaran',
        'ket_penolakan',
    ];
    public $timestamps = false;

    public function bilik()
    {
        return $this->belongsTo(Bilik::class, 'bilik_id');
    }
}
