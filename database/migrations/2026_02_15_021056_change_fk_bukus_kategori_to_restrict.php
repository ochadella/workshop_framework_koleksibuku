<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bukus', function (Blueprint $table) {
            // drop foreign key lama (yang masih CASCADE)
            $table->dropForeign(['kategori_id']);

            // bikin lagi foreign key baru dengan RESTRICT
            $table->foreign('kategori_id')
                ->references('id')
                ->on('kategoris')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bukus', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);

            // balikin ke CASCADE kalau rollback
            $table->foreign('kategori_id')
                ->references('id')
                ->on('kategoris')
                ->cascadeOnDelete();
        });
    }
};
