<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookController extends Controller
{
    private $books = [
        1 => [
            "judul" => "Cara membuat website",
            "penulis" => "Tatang",
            "status" => "Tersedia"
        ],
        2 => [
            "judul" => "Siapa itu Uruha Rushia",
            "penulis" => "Sucipto",
            "status" => "Dipinjam"
        ],
        3 => [
            "judul" => "Apa itu Hololive",
            "penulis" => "joko",
            "status" => "Tersedia"
        ],
    ];

    public function index()
    {
        $books = $this->books;
        return view('books.index', compact('books'));
    }

    public function show($id)
    {
        $book = $this->books[$id] ?? null; 
        if (!$book) {
            return abort(404); 
        }
        return view('books.show', compact('book'));
    }

    public function available()
    {
        $availableBooks = array_filter($this->books, function ($book) {
            return $book['status'] === 'Tersedia';
        });
        return view('books.available', compact('availableBooks'));
    }
}