@extends('layouts.main')

@section('title', 'Daftar Barang')

@section('content')
<h1>Daftar Barang</h1>

<form method="GET" action="{{ route('barang.index') }}">
    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari nama barang...">
    <button type="submit">Search</button>
</form>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Jumlah</th>
            <th>Tanggal Masuk</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($barangs as $b)
            <tr>
                <td>{{ ($barangs->currentPage() - 1) * $barangs->perPage() + $loop->iteration }}</td>
                <td>{{ $b->kode_barang }}</td>
                <td>{{ $b->nama_barang }}</td>
                <td>{{ $b->kategori }}</td>
                <td>{{ $b->jumlah }}</td>
                <td>{{ $b->tanggal_masuk }}</td>
                <td>
                    <a href="{{ route('barang.show', $b->id) }}">Detail</a>
                    <a href="{{ route('barang.edit', $b->id) }}">Edit</a>
                    <form action="{{ route('barang.destroy', $b->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Yakin mau hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">Belum ada data.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div>
    {{ $barangs->appends(request('q'))->links() }}
</div>
@endsection
