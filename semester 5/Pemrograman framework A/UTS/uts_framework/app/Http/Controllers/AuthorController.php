<?php

// File: app/Http/Controllers/AuthorController.php
namespace App\Http\Controllers;

use App\Models\Author; // Pastikan model Author di-import
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    // Menampilkan daftar semua penulis
    public function index()
    {
        $authors = Author::latest()->paginate(10);
        return view('authors.index', compact('authors'));
    }

    // Menampilkan form untuk membuat penulis baru
    public function create()
    {
        return view('authors.create');
    }

    // Menyimpan penulis baru ke database
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255|unique:authors',
        ]);

        Author::create($request->all());

        return redirect()->route('authors.index')->with('success', 'Penulis berhasil ditambahkan.');
    }

    // Menampilkan detail satu penulis (termasuk buku-bukunya)
    public function show(Author $author)
    {
        // Memuat relasi 'books'
        $author->load('books'); 
        return view('authors.show', compact('author'));
    }

    // Menampilkan form untuk mengedit penulis
    public function edit(Author $author)
    {
        return view('authors.edit', compact('author'));
    }

    // Memperbarui data penulis di database
    public function update(Request $request, Author $author)
    {
        $request->validate([
            // 'unique' ditambahkan agar bisa di-update
            'name' => 'required|string|max:255|unique:authors,name,' . $author->id,
        ]);

        $author->update($request->all());

        return redirect()->route('authors.index')->with('success', 'Data penulis berhasil diperbarui.');
    }

    // Menghapus penulis dari database
    public function destroy(Author $author)
    {
        // Cek dulu apakah penulis punya buku
        if ($author->books()->count() > 0) {
            return redirect()->route('authors.index')->with('error', 'Penulis tidak bisa dihapus karena masih memiliki buku terkait.');
        }

        $author->delete();
        return redirect()->route('authors.index')->with('success', 'Penulis berhasil dihapus.');
        // Catatan: Jika Anda menggunakan onDelete('cascade') di migration, 
        // Anda bisa langsung hapus tanpa cek, dan semua bukunya akan ikut terhapus.
        // Pilihan di atas lebih aman agar tidak salah hapus.
    }
}