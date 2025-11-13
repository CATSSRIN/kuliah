<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    // Menampilkan daftar semua barang
    public function index()
    {
        $search = request('search');
        if ($search) {
            $barangs = Barang::where('nama_barang', 'like', '%'.$search.'%')->paginate(10);
        } else {
            $barangs = Barang::paginate(10);
        }
        return view('barang.index', ['barangs' => $barangs, 'search' => $search]);
    }

    // Menampilkan form untuk membuat barang baru
    public function create()
    {
        return view('barang.create');
    }

    // Menyimpan barang baru ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|unique:barangs',
            'nama_barang' => 'required|string',
            'kategori' => 'required|string',
            'jumlah' => 'required|integer|min:1',
            'tanggal_masuk' => 'required|date',
        ]);

        Barang::create($validated);
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    // Menampilkan detail barang
    public function show(Barang $barang)
    {
        return view('barang.show', ['barang' => $barang]);
    }

    // Menampilkan form edit barang
    public function edit(Barang $barang)
    {
        return view('barang.edit', ['barang' => $barang]);
    }

    // Update barang di database
    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|unique:barangs,kode_barang,'.$barang->id,
            'nama_barang' => 'required|string',
            'kategori' => 'required|string',
            'jumlah' => 'required|integer|min:1',
            'tanggal_masuk' => 'required|date',
        ]);

        $barang->update($validated);
        return redirect()->route('barang.index')->with('success', 'Barang berhasil diupdate!');
    }

    // Menghapus barang dari database
    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus!');
    }
}
