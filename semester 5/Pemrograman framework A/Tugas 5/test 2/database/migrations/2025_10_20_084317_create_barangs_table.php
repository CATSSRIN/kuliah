<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::create('barangs', function (Blueprint $table) {
        $table->id();
        $table->string('kode_barang')->unique(); // 'unique' [cite: 468]
        $table->string('nama_barang');
        $table->string('kategori');
        $table->integer('jumlah'); // 'jumlah' sebaiknya integer [cite: 471]
        $table->string('kondisi'); // 'kondisi' (mis: "Baik", "Rusak") [cite: 472]
        $table->date('tanggal_masuk'); // 'date' lebih cocok untuk tanggal [cite: 473]
        $table->timestamps(); // Ini akan membuat created_at dan updated_at
    });
}

/**
 * Reverse the migrations.
 *
 * @return void
 */
    public function down()
    {
        Schema::dropIfExists('barangs');
    }
};
