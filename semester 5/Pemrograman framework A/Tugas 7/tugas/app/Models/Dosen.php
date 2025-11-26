<?php

namespace App\Models; // <-- PASTIKAN INI BENAR (App\Models)

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model // <-- PASTIKAN NAMA CLASS INI BENAR (Dosen)
{
    use HasFactory;

    /**
     * Kolom yang BOLEH diisi.
     *
     * @var array
     */
    protected $fillable = [
        'nip',
        'nama',
        'email',
        'bidang_keahlian',
        // tambahkan kolom lain jika ada
    ];
}