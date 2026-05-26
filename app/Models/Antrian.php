<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    protected $fillable = [
        'nama',
        'kode_poli',
        'nama_poli',
        'nomor_antrian',
        'urutan',
        'status',
    ];

    // Daftar poli yang tersedia
    public static function daftarPoli(): array
    {
        return [
            'A' => 'Poli Umum',
            'B' => 'Poli Gigi',
            'C' => 'Poli Kandungan',
            'D' => 'Poli Anak',
        ];
    }

    // Generate nomor antrian berikutnya untuk poli tertentu
    public static function generateNomor(string $kodePoli): array
    {
        // Ambil urutan terakhir untuk poli ini hari ini
        $terakhir = self::where('kode_poli', $kodePoli)
            ->whereDate('created_at', today())
            ->max('urutan') ?? 0;

        $urutan = $terakhir + 1;
        $nomor  = $kodePoli . str_pad($urutan, 3, '0', STR_PAD_LEFT); // A001, A002, dst

        return ['urutan' => $urutan, 'nomor' => $nomor];
    }
}