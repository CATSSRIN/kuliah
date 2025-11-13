<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * READ - Menampilkan semua barang (dengan pagination & search)
     */
    public function index(Request $request)
    {
        $q = $request->query('q');
        if ($q) {
            // Sesuaikan 'nama_barang' dengan field yang ingin dicari
            $barangs = Barang::where('nama_barang', 'like', '%' . $q . '%') 
                             ->orWhere('kode_barang', 'like', '%' . $q . '%')
                             ->paginate(10); // [cite: 24]
        } else {
            $barangs = Barang::paginate(10); // [cite: 25]
        }
        return view('barang.index', compact('barangs', 'q')); // [cite: 27]
    }

    /**
     * CREATE - Menampilkan form untuk menambah barang
     */
    public function create()
    {
        return view('barang.create'); // [cite: 28]
    }

    /**
     * STORE - Menyimpan barang baru ke database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|unique:barangs,kode_barang', // [cite: 29]
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'kondisi' => 'required|string|max:255',
            'tanggal_masuk' => 'required|date',
        ]);

        Barang::create($validated); // [cite: 29]

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * SHOW - Menampilkan detail satu barang
     */
    public function show(Barang $barang)
    {
        return view('barang.show', compact('barang')); // [cite: 30]
    }

    /**
     * EDIT - Menampilkan form untuk mengedit barang
     */
    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang')); // [cite: 31]
    }

    /**
     * UPDATE - Menyimpan perubahan data barang ke database
     */
    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            // [cite: 32] (Rule unique diupdate untuk mengabaikan ID saat ini)
            'kode_barang' => 'required|string|unique:barangs,kode_barang,' . $barang->id, 
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'kondisi' => 'required|string|max:255',
            'tanggal_masuk' => 'required|date',
        ]);

        $barang->update($validated); // [cite: 32]

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diupdate.');
    }

    /**
     * DESTROY - Menghapus barang dari database
     */
    public function destroy(Barang $barang)
    {
        $barang->delete(); // [cite: 33]
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}