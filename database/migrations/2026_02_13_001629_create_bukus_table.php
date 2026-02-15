<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bukus', function (Blueprint $table) {
            $table->id(); // idbuku (otomatis)
            $table->string('kode', 20)->unique();
            $table->string('judul', 500);
            $table->string('pengarang', 200);

            // relasi ke kategoris
            $table->foreignId('kategori_id')
                ->constrained('kategoris')
                ->restrictOnDelete(); // RESTRICT: kategori gak bisa dihapus kalau masih dipakai buku

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
