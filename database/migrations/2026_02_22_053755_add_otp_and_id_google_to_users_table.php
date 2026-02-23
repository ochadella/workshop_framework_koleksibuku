<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // OTP 6 digit (string supaya aman kalau ada leading zero)
            if (!Schema::hasColumn('users', 'otp')) {
                $table->string('otp', 6)->nullable()->after('password');
            }

            // Google ID
            if (!Schema::hasColumn('users', 'id_google')) {
                $table->string('id_google')->nullable()->unique()->after('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'otp')) {
                $table->dropColumn('otp');
            }

            if (Schema::hasColumn('users', 'id_google')) {
                $table->dropUnique(['id_google']);
                $table->dropColumn('id_google');
            }
        });
    }
};