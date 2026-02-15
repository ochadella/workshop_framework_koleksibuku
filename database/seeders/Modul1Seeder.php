<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Buku;

class Modul1Seeder extends Seeder
{
    public function run(): void
    {
        $novel = Kategori::create(['nama_kategori' => 'Novel']);
        $bio   = Kategori::create(['nama_kategori' => 'Biografi']);
        $komik = Kategori::create(['nama_kategori' => 'Komik']);

        Buku::create([
            'kode' => 'NV-01',
            'judul' => 'Home Sweet Loan',
            'pengarang' => 'Almira Bastari',
            'kategori_id' => $novel->id,
        ]);

        Buku::create([
            'kode' => 'BO-01',
            'judul' => 'Mohammad Hatta, Untuk Negeriku',
            'pengarang' => 'Taufik Abdullah',
            'kategori_id' => $bio->id,
        ]);

        Buku::create([
            'kode' => 'NV-02',
            'judul' => 'Keajaiban Toko Kelontong Namiya',
            'pengarang' => 'Keigo Higashino',
            'kategori_id' => $novel->id,
        ]);
    }
}
