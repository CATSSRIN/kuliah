<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyek extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul', 'deskripsi', 'mahasiswa_id', 'dosen_id', 'dokumen', 'status'
    ];

    /**
     * Relasi belongs-to ke Mahasiswa
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    /**
     * Relasi belongs-to ke Dosen
     */
    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }
}