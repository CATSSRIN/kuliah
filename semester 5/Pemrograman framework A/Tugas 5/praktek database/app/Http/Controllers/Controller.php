<?php
namespace App\Http\Controllers;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    // READ - index
    public function index(Request $request)
    {
        // optional: search & pagination
        $q = $request->query('q');
        if ($q) {
            $mahasiswas = Mahasiswa::where('nama', 'like', '%'.$q.'%')->paginate(10);
        } else {
            $mahasiswas = Mahasiswa::paginate(10);
        }
        return view('mahasiswa.index', compact('mahasiswas', 'q'));
    }

    // CREATE - show form
    public function create()
    {
        return view('mahasiswa.create');
    }

    // STORE - save new
    public function store(Request $request)
    {
        $validated = $request->validate([
            'npm' => 'required|numeric|unique:mahasiswas,npm',
            'nama' => 'required|string|max:255',
            'prodi' => 'required|string|max:255',
        ]);

        Mahasiswa::create($validated);

        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    // SHOW - detail
    public function show(Mahasiswa $mahasiswa)
    {
        return view('mahasiswa.show', compact('mahasiswa'));
    }

    // EDIT - form edit
    public function edit(Mahasiswa $mahasiswa)
    {
        return view('mahasiswa.edit', compact('mahasiswa'));
    }

    // UPDATE - simpan edit
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $validated = $request->validate([
            'npm' => 'required|numeric|unique:mahasiswas,npm,'.$mahasiswa->id,
            'nama' => 'required|string|max:255',
            'prodi' => 'required|string|max:255',
        ]);

        $mahasiswa->update($validated);

        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa berhasil diupdate.');
    }

    // DESTROY - hapus
    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();
        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa berhasil dihapus.');
    }
}
