<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'nim', 'email', 'foto'
    ];

    /**
     * Relasi one-to-one ke Proyek [cite: 502]
     */
    public function proyek()
    {
        return $this->hasOne(Proyek::class);
    }
}