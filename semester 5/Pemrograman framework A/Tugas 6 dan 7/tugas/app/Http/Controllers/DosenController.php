<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen; // Pastikan ini ada dan modelnya ditemukan

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // GANTI DARI all() MENJADI paginate()
        // Ini akan mengambil data (misal 10 per halaman) dan
        // menyediakan link pagination
        $dosens = Dosen::paginate(10); 
        
        // Kirim data ke view
        return view('dosen.index', ['dosens' => $dosens]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dosen.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'nip' => 'required|string|unique:dosens|max:18',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:dosens',
            'bidang_keahlian' => 'nullable|string',
        ]);

        // Simpan data
        Dosen::create($request->all());

        // Redirect
        return redirect()->route('dosen.index')
                         ->with('success', 'Data dosen berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // FindOrFail akan otomatis error 404 jika ID tidak ditemukan
        $dosen = Dosen::findOrFail($id);
        return view('dosen.show', ['dosen' => $dosen]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dosen = Dosen::findOrFail($id);
        return view('dosen.edit', ['dosen' => $dosen]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validasi (email harus unik, tapi abaikan email milik dosen ini sendiri)
        $request->validate([
            'nip' => 'required|string|max:18|unique:dosens,nip,' . $id,
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:dosens,email,' . $id,
            'bidang_keahlian' => 'nullable|string',
        ]);

        // Cari dan update
        $dosen = Dosen::findOrFail($id);
        $dosen->update($request->all());

        // Redirect
        return redirect()->route('dosen.index')
                         ->with('success', 'Data dosen berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Cari dan hapus
        $dosen = Dosen::findOrFail($id);
        $dosen->delete();

        // Redirect
        return redirect()->route('dosen.index')
                         ->with('success', 'Data dosen berhasil dihapus.');
    }
}