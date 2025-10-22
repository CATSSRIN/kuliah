@extends('layouts.main')

@section('title', 'Daftar Barang Inventaris')

@section('content')
    <h1>Daftar Barang Inventaris</h1>

    {{-- Form Pencarian --}}
    <form method="GET" action="{{ route('barang.index') }}">
        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari nama barang...">
        <button type="submit">Search</button>
    </form>
    <br>
    <a href="{{ route('barang.create') }}">Tambah Barang Baru</a>
    <br><br>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Jumlah</th>
                <th>Kondisi</th>
                <th>Tgl. Masuk</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            {{-- Loop data barang --}}
            @forelse($barangs as $barang)
                <tr>
                    {{-- Pagination number --}}
                    <td>{{ $loop->iteration + ($barangs->currentPage() - 1) * $barangs->perPage() }}</td>
                    <td>{{ $barang->kode_barang }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->kategori }}</td>
                    <td>{{ $barang->jumlah }}</td>
                    <td>{{ $barang->kondisi }}</td>
                    <td>{{ $barang->tanggal_masuk }}</td>
                    <td>
                        {{-- Aksi --}}
                        <a href="{{ route('barang.show', $barang->id) }}">Detail</a> |
                        <a href="{{ route('barang.edit', $barang->id) }}">Edit</a> |
                        <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                {{-- Jika data kosong --}}
                <tr>
                    <td colspan="8">Belum ada data barang.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Link Paginasi --}}
    <div>
        {{ $barangs->appends(['q' => $q ?? ''])->links() }}
    </div>
@endsection