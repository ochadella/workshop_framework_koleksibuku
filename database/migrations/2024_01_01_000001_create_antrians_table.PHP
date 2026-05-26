<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('antrians', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode_poli');       // A, B, C, D
            $table->string('nama_poli');       // Poli Umum, Poli Gigi, dst
            $table->string('nomor_antrian');   // A001, B002, dst
            $table->integer('urutan');         // 1, 2, 3 (per poli)
            $table->enum('status', ['menunggu', 'dipanggil', 'terlambat', 'selesai'])->default('menunggu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('antrians');
    }
};