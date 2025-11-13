@extends('layouts.main')

@section('title', 'Tambah Barang')

@section('content')
<h1>Tambah Barang</h1>

@if($errors->any)
    <div style="color:red">
        <ul>
        @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('barang.store') }}">
    @csrf
    <div>
        <label>Kode Barang</label>
        <input type="text" name="kode_barang" value="{{ old('kode_barang') }}" required>
    </div>
    <div>
        <label>Nama Barang</label>
        <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" required>
    </div>
    <div>
        <label>Kategori</label>
        <input type="text" name="kategori" value="{{ old('kategori') }}" required>
    </div>
    <div>
        <label>Jumlah</label>
        <input type="number" name="jumlah" value="{{ old('jumlah') }}" required>
    </div>
    <div>
        <label>Tanggal Masuk</label>
        <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}" required>
    </div>
    <button type="submit">Simpan</button>
</form>
@endsection
