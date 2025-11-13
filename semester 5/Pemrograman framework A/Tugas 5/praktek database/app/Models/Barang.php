<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barangs';
    protected $fillable = ['kode_barang', 'nama_barang', 'kategori', 'jumlah', 'tanggal_masuk'];
}
