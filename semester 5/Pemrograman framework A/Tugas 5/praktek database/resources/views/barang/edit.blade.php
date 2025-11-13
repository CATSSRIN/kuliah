@extends('layouts.main')

@section('title', 'Edit Barang')

@section('content')
<h1>Edit Barang</h1>

@if($errors->any)
    <div style="color:red">
        <ul>
        @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('barang.update', $barang->id) }}">
    @csrf
    @method('PUT')
    <div>
        <label>Kode Barang</label>
        <input type="text" name="kode_barang" value="{{ old('kode_barang', $barang->kode_barang) }}" required>
    </div>
    <div>
        <label>Nama Barang</label>
        <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" required>
    </div>
    <div>
        <label>Kategori</label>
        <input type="text" name="kategori" value="{{ old('kategori', $barang->kategori) }}" required>
    </div>
    <div>
        <label>Jumlah</label>
        <input type="number" name="jumlah" value="{{ old('jumlah', $barang->jumlah) }}" required>
    </div>
    <div>
        <label>Tanggal Masuk</label>
        <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', $barang->tanggal_masuk) }}" required>
    </div>
    <button type="submit">Update</button>
</form>
@endsection
