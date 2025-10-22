<?php

namespace App\Http\Controllers;

use App\Models\Barang; // <-- JANGAN LUPA IMPORT
use Illuminate\Http\Request;

class BarangController extends Controller
{
    // READ - Menampilkan semua barang (dengan pagination & search)
    public function index(Request $request)
    {
        $q = $request->query('q');
        if ($q) {
            $barangs = Barang::where('nama_barang', 'like', '%' . $q . '%')->paginate(10);
        } else {
            $barangs = Barang::paginate(10);
        }
        return view('barang.index', compact('barangs', 'q')); // Kirim ke view barang.index
    }

    // CREATE - Menampilkan form tambah barang
    public function create()
    {
        return view('barang.create'); // Tampilkan view barang.create
    }

    // STORE - Menyimpan data barang baru
    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'kode_barang' => 'required|string|unique:barangs,kode_barang',
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'kondisi' => 'required|string',
            'tanggal_masuk' => 'required|date',
        ]);

        Barang::create($validated); // Simpan data baru

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    // SHOW - Menampilkan detail satu barang
    public function show(Barang $barang)
    {
        return view('barang.show', compact('barang')); // Tampilkan view barang.show
    }

    // EDIT - Menampilkan form edit barang
    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang')); // Tampilkan view barang.edit
    }

    // UPDATE - Menyimpan perubahan data barang
    public function update(Request $request, Barang $barang)
    {
        // Validasi data (kode_barang harus unik, kecuali untuk dirinya sendiri)
        $validated = $request->validate([
            'kode_barang' => 'required|string|unique:barangs,kode_barang,' . $barang->id,
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'kondisi' => 'required|string',
            'tanggal_masuk' => 'required|date',
        ]);

        $barang->update($validated); // Update data

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diupdate.');
    }

    // DESTROY - Menghapus data barang
    public function destroy(Barang $barang)
    {
        $barang->delete(); // Hapus data
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}