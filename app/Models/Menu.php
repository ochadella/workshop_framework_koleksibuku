<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';

    protected $fillable = [
        'vendor_id',
        'nama_menu',
        'harga',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'idmenu', 'id');
    }
}