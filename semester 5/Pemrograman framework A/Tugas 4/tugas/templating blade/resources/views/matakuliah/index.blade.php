{{-- Menggunakan layout utama --}}
@extends('layouts.main')

{{-- Mendefinisikan bagian konten --}}
@section('content')

    <h2>Daftar Mata Kuliah</h2>

    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Mata Kuliah</th>
                <th>SKS</th>
                <th>Dosen Pengampu</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            {{-- Loop data mata kuliah, gunakan @forelse untuk handle jika data kosong --}}
            @forelse ($matakuliah as $mk)
                <tr>
                    <td>{{ $mk['kode'] }}</td>
                    <td>{{ $mk['nama'] }}</td>
                    <td>{{ $mk['sks'] }}</td>
                    <td>
                        {{-- Cek apakah ada dosen pengampu --}}
                        @if (count($mk['dosen']) > 0)
                            <ul>
                                {{-- Loop untuk menampilkan semua dosen --}}
                                @foreach ($mk['dosen'] as $dosen)
                                    <li>{{ $dosen }}</li>
                                @endforeach
                            </ul>
                        @else
                            {{-- Pesan jika tidak ada dosen --}}
                            <em>Tidak ada dosen pengampu</em>
                        @endif
                    </td>
                    <td>
                        {{-- Kondisi untuk badge hijau atau abu-abu --}}
                        @if (count($mk['dosen']) > 3)
                            <span class="badge badge-hijau">Mata Kuliah Inti</span>
                        @else
                            <span class="badge badge-abu">Pilihan</span>
                        @endif
                    </td>
                </tr>
            @empty
                {{-- Pesan jika data mata kuliah kosong --}}
                <tr>
                    <td colspan="5" style="text-align: center;">Belum ada mata kuliah tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

@endsection