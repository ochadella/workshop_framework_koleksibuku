<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendors';

    protected $fillable = [
        'nama_vendor',
        'iduser',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class, 'vendor_id');
    }
}