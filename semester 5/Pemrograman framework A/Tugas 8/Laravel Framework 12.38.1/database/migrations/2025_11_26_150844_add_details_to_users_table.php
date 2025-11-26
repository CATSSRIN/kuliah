<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. Tambahkan kolom ROLE terlebih dahulu (karena belum ada)
            // Kita taruh setelah password supaya rapi
            $table->string('role')->default('user')->after('password');

            // 2. Baru tambahkan kolom lainnya
            $table->string('nomor_hp')->nullable()->after('email');
            $table->text('alamat')->nullable()->after('nomor_hp');
            
            // 3. Status ditaruh setelah role (sekarang aman karena role sudah dibuat di atas)
            $table->enum('status', ['active', 'suspended'])->default('active')->after('role');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus semua kolom tambahan jika rollback
            $table->dropColumn(['role', 'nomor_hp', 'alamat', 'status']);
        });
    }
};