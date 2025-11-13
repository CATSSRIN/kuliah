<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        if ($q) {
            $barangs = Barang::where('nama_barang', 'like', '%' . $q . '%')->paginate(10);
        } else {
            $barangs = Barang::paginate(10);
        }
        return view('barang.index', compact('barangs', 'q'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|numeric|unique:barangs,kode_barang',
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'jumlah' => 'required|numeric',
            'tanggal_masuk' => 'required|date',
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
            'kode_barang' => 'required|numeric|unique:barangs,kode_barang,' . $barang->id,
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'jumlah' => 'required|numeric',
            'tanggal_masuk' => 'required|date',
        ]);

        $barang->update($validated);
        return redirect()->route('barang.index')->with('success', 'Barang berhasil diupdate.');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}
