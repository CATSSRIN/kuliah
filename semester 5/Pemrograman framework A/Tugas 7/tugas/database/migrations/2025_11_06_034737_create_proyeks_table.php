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
        Schema::create('proyeks', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi');
            
            // Relasi ke Mahasiswa (One-to-One) [cite: 502]
            $table->foreignId('mahasiswa_id')->unique()->constrained()->onDelete('cascade');
            
            // Relasi ke Dosen (One-to-Many) [cite: 503]
            $table->foreignId('dosen_id')->constrained()->onDelete('cascade');
            
            $table->string('dokumen')->nullable(); // Sesuai aturan validasi [cite: 512]
            $table->string('status')->default('pending'); // Contoh: pending, selesai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyeks');
    }
};