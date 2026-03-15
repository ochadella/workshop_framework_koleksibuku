<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $fillable = [
        'tanggal',
        'total',
    ];

    public function details()
    {
        return $this->hasMany(PenjualanDetail::class);
    }
}