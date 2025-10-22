<?php

// File: app/Models/Book.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'author_id', 'description']; // Kolom yang diizinkan

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}