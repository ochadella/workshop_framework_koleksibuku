<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id(); // primary key auto increment
            
            // ID Barang otomatis dari trigger PostgreSQL
            $table->string('id_barang', 30)->unique();
            
            // Data utama produk / buku
            $table->string('nama_barang');
            $table->integer('harga');
            
            // Opsional (boleh dipakai nanti untuk PDF kalau mau)
            $table->text('deskripsi')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};