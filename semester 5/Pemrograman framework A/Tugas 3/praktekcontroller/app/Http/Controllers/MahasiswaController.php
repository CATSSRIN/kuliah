<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    protected $data = [
        ['id'=>1, 'nama'=>'Andi', 'nim'=>'1001'],
        ['id'=>2, 'nama'=>'Budi', 'nim'=>'1002'],
        ['id'=>3, 'nama'=>'Citra', 'nim'=>'1003'],
    ];

    public function index() {
        $mahasiswa = $this->data;
        return view('mahasiswa.index', compact('mahasiswa'));
    }

    public function show($id) {
        $m = collect($this->data)->firstWhere('id', (int)$id);
        if (!$m) abort(404);
        return view('mahasiswa.show', compact('m'));
    }
}


