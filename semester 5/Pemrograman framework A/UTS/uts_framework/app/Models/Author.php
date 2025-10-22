<?php

// File: app/Models/Author.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = ['name']; // Izinkan mass assignment untuk kolom 'name'

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}