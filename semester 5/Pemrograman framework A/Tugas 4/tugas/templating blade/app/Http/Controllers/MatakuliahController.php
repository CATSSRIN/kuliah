<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MatakuliahController extends Controller
{
    // Fungsi untuk menampilkan data mata kuliah
    public function index()
    {
        // Siapkan data mata kuliah (minimal 4)
        $dataMatakuliah = [
            [
                'kode' => 'IF221225',
                'nama' => 'ROBOTIKA',
                'sks' => 3,
                'dosen' => [
                    'Budi Nugroho, S.Kom, M.Kom (PIC)'
                ]
            ],
            [
                'kode' => 'IF221226',
                'nama' => 'MIKROKONTROLER',
                'sks' => 3,
                'dosen' => [

                ]
            ],
            [
                'kode' => 'IF221105',
                'nama' => 'SISTEM DIGITAL',
                'sks' => 3,
                'dosen' => [
                    'Agung Mustika Rizki, S.Kom, M.Kom. (PIC)',
                    'Andreas Nugroho S, S.Kom, M.Kom.',
                    'Henni Endah Wahanani, S.T, M.Kom.',
                    'M. Muharrom A.H, S.Kom., M.Kom.'
                ]
            ],
            [
                'kode' => 'IF221102',
                'nama' => 'MATEMATIKA KOMPUTASI',
                'sks' => 3,
                'dosen' => [
                    'Andreas Nugroho S, S.Kom, M.Kom (PIC)',
                    'Agung Mustika Rizki, S.Kom., M.Kom.',
                    'Eka Prakarsa Mandyartha, ST, M.Kom.',
                    'Pratama Wirya Atmaja, S.Kom., M.Kom.'
                ]
                ],
        ];

        // Kirim data ke view dan tampilkan
        return view('matakuliah.index', [
            'title' => 'Daftar Mata Kuliah',
            'matakuliah' => $dataMatakuliah
        ]);
    }
}