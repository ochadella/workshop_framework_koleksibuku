<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $fillable = [
        'tanggal',
        'total',
        'status_pembayaran',
        'metode_pembayaran',
        'midtrans_order_id',
        'snap_token',
        'paid_at',
    ];

    public function details()
    {
        return $this->hasMany(PenjualanDetail::class);
    }
}