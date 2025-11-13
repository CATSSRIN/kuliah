<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $search = request()->query('search');
        $barangs = Barang::when($search, function ($query) use ($search) {
            return $query->where('nama_barang', 'like', "%{$search}%")
                        ->orWhere('kode_barang', 'like', "%{$search}%");
        })->paginate(10);

        return view('barang.index', compact('barangs', 'search'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang',
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'tanggal_masuk' => 'required|date',
        ], [
            'kode_barang.unique' => 'Kode barang sudah terdaftar.',
            'kode_barang.required' => 'Kode barang harus diisi.',
            'nama_barang.required' => 'Nama barang harus diisi.',
            'kategori.required' => 'Kategori harus diisi.',
            'jumlah.required' => 'Jumlah harus diisi.',
            'tanggal_masuk.required' => 'Tanggal masuk harus diisi.',
        ]);

        Barang::create($validated);
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Barang $barang)
    {
        return view('barang.show', compact('barang'));
    }

    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang,' . $barang->id,
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'tanggal_masuk' => 'required|date',
        ]);

        $barang->update($validated);
        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}