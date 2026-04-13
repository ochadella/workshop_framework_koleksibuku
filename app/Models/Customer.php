<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'nama_customer',
        'email',
        'no_hp',
        'alamat',
        'foto_blob',
        'foto_path',
    ];
}