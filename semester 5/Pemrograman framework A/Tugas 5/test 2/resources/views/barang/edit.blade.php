@extends('layouts.main')

@section('title', 'Edit Barang')

@section('content')
    <h1>Edit Barang: {{ $barang->nama_barang }}</h1>

    {{-- Menampilkan Error Validasi [cite: 405-411] --}}
    @if ($errors->any())
        <div style="color:red">
            <strong>Whoops! Ada masalah dengan input Anda:</strong>
            <ul>
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('barang.update', $barang->id) }}"> [cite: 412]
        @csrf
        @method('PUT') {{-- [cite: 414] --}}

        <div>
            <label>Kode Barang</label><br>
            {{-- Mengisi value dengan data lama [cite: 416] --}}
            <input type="text" name="kode_barang" value="{{ old('kode_barang', $barang->kode_barang) }}">
        </div>
        <div>
            <label>Nama Barang</label><br>
            <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}">
        </div>
        <div>
            <label>Kategori</label><br>
            <input type="text" name="kategori" value="{{ old('kategori', $barang->kategori) }}">
        </div>
        <div>
            <label>Jumlah</label><br>
            <input type="number" name="jumlah" value="{{ old('jumlah', $barang->jumlah) }}">
        </div>
         <div>
            <label>Kondisi</label><br>
            <input type="text" name="kondisi" value="{{ old('kondisi', $barang->kondisi) }}">
        </div>
        <div>
            <label>Tanggal Masuk</label><br>
            <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', $barang->tanggal_masuk) }}">
        </div>
        <br>
        <button type="submit">Update</button>
    </form>
@endsection