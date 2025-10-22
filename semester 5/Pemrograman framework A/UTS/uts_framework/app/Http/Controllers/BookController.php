<?php

// File: app/Http/Controllers/BookController.php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author; // Jangan lupa import model Author
use Illuminate\Http\Request;

class BookController extends Controller
{
    // Menampilkan daftar semua buku
    public function index()
    {
        $books = Book::with('author')->latest()->paginate(10); // Ambil buku dengan relasi penulis
        return view('books.index', compact('books'));
    }

    // Menampilkan form untuk membuat buku baru
    public function create()
    {
        $authors = Author::all(); // Ambil semua data penulis untuk dropdown
        return view('books.create', compact('authors'));
    }

    // Menyimpan buku baru ke database
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'description' => 'nullable|string',
        ]);

        Book::create($request->all()); // Simpan data baru

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    // Menampilkan detail satu buku
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    // Menampilkan form untuk mengedit buku
    public function edit(Book $book)
    {
        $authors = Author::all();
        return view('books.edit', compact('book', 'authors'));
    }

    // Memperbarui data buku di database
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'description' => 'nullable|string',
        ]);

        $book->update($request->all());

        return redirect()->route('books.index')->with('success', 'Data buku berhasil diperbarui.');
    }

    // Menghapus buku dari database
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }
};