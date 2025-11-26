<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProyekController extends Controller
{
    public function index()
    {
        // Ambil data proyek dengan relasi mahasiswa dan dosen
        $proyeks = Proyek::with('mahasiswa', 'dosen')->paginate(10);
        return view('proyek.index', compact('proyeks'));
    }

    public function create()
    {
        // Ambil data mahasiswa dan dosen untuk dropdown
        $mahasiswas = Mahasiswa::all();
        $dosens = Dosen::all();
        return view('proyek.create', compact('mahasiswas', 'dosens'));
    }

    public function store(Request $request)
    {
        // Validasi form [cite: 509, 510, 511, 512]
        $request->validate([
            'judul' => 'required|min:3',
            'deskripsi' => 'required',
            'mahasiswa_id' => 'required|unique:proyeks', // unique agar 1 mhs 1 proyek
            'dosen_id' => 'required',
            'status' => 'required',
            'dokumen' => 'nullable|mimes:pdf,docx|max:2048', // Max 2MB
        ]);

        $data = $request->all();
        
        // Cek jika ada file dokumen diupload
        if ($request->hasFile('dokumen')) {
            $data['dokumen'] = $request->file('dokumen')->store('dokumen_proyek', 'public');
        }

        Proyek::create($data);

        return redirect()->route('proyek.index')->with('success', 'Proyek berhasil ditambahkan.');
    }

    public function edit(Proyek $proyek)
    {
        // Ambil data mahasiswa dan dosen untuk dropdown
        $mahasiswas = Mahasiswa::all();
        $dosens = Dosen::all();
        return view('proyek.edit', compact('proyek', 'mahasiswas', 'dosens'));
    }

    public function update(Request $request, Proyek $proyek)
    {
        // Validasi form [cite: 509, 510, 511, 512]
        $request->validate([
            'judul' => 'required|min:3',
            'deskripsi' => 'required',
            'mahasiswa_id' => 'required',
            'dosen_id' => 'required',
            'status' => 'required',
            'dokumen' => 'nullable|mimes:pdf,docx|max:2048', // Max 2MB
        ]);

        $data = $request->all();

        // Cek jika ada file dokumen baru diupload
        if ($request->hasFile('dokumen')) {
            // Hapus dokumen lama jika ada
            if ($proyek->dokumen && Storage::disk('public')->exists($proyek->dokumen)) {
                Storage::disk('public')->delete($proyek->dokumen);
            }
            // Simpan dokumen baru
            $data['dokumen'] = $request->file('dokumen')->store('dokumen_proyek', 'public');
        }

        $proyek->update($data);

        return redirect()->route('proyek.index')->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroy(Proyek $proyek)
    {
        // Hapus dokumen dari storage jika ada [cite: 264]
        if ($proyek->dokumen && Storage::disk('public')->exists($proyek->dokumen)) {
            Storage::disk('public')->delete($proyek->dokumen);
        }

        $proyek->delete();

        return redirect()->route('proyek.index')->with('success', 'Proyek berhasil dihapus.');
    }
}