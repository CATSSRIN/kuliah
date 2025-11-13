@extends('layouts.main')
@section('title','Edit Barang')
@section('content')
<h1>Edit Barang: {{ $barang->nama_barang }}</h1>

<form method="POST" action="{{ route('barang.update', $barang->id) }}">
  @csrf
  @method('PUT')
  <div>
    <label>Kode Barang</label>
    <input type="text" name="kode_barang" value="{{ old('kode_barang', $barang->kode_barang) }}">
  </div>
  <div>
    <label>Nama Barang</label>
    <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}">
  </div>
  <div>
    <label>Kategori</label>
    <input type="text" name="kategori" value="{{ old('kategori', $barang->kategori) }}">
  </div>
  <div>
    <label>Jumlah</label>
    <input type="number" name="jumlah" value="{{ old('jumlah', $barang->jumlah) }}">
  </div>
  <div>
    <label>Kondisi</label>
    <input type="text" name="kondisi" value="{{ old('kondisi', $barang->kondisi) }}">
  </div>
  <div>
    <label>Tanggal Masuk</label>
    <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', $barang->tanggal_masuk) }}">
  </div>
  <button type="submit">Update</button>
</form>
@endsection