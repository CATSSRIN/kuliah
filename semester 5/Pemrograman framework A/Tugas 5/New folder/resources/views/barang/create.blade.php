@extends('layouts.main')
@section('title','Tambah Barang')
@section('content')
<h1>Tambah Barang</h1>

<form method="POST" action="{{ route('barang.store') }}">
  @csrf
  <div>
    <label>Kode Barang</label>
    <input type="text" name="kode_barang" value="{{ old('kode_barang') }}">
  </div>
  <div>
    <label>Nama Barang</label>
    <input type="text" name="nama_barang" value="{{ old('nama_barang') }}">
  </div>
  <div>
    <label>Kategori</label>
    <input type="text" name="kategori" value="{{ old('kategori') }}">
  </div>
  <div>
    <label>Jumlah</label>
    <input type="number" name="jumlah" value="{{ old('jumlah') }}">
  </div>
  <div>
    <label>Kondisi</label>
    <input type="text" name="kondisi" value="{{ old('kondisi') }}" placeholder="cth: Baik, Rusak Ringan">
  </div>
  <div>
    <label>Tanggal Masuk</label>
    <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}">
  </div>
  <button type="submit">Simpan</button>
</form>
@endsection