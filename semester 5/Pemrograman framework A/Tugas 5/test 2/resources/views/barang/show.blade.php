@extends('layouts.main')

@section('title', 'Detail Barang')

@section('content')
    <h1>Detail Barang</h1>

    <p><strong>ID:</strong> {{ $barang->id }}</p>
    <p><strong>Kode Barang:</strong> {{ $barang->kode_barang }}</p>
    <p><strong>Nama Barang:</strong> {{ $barang->nama_barang }}</p>
    <p><strong>Kategori:</strong> {{ $barang->kategori }}</p>
    <p><strong>Jumlah:</strong> {{ $barang->jumlah }}</P>
    <p><strong>Kondisi:</strong> {{ $barang->kondisi }}</P>
    <p><strong>Tanggal Masuk:</strong> {{ $barang->tanggal_masuk }}</P>
    <p><strong>Dibuat pada:</strong> {{ $barang->created_at }}</p>
    <p><strong>Diupdate pada:</strong> {{ $barang->updated_at }}</p>
    <br>
    <a href="{{ route('barang.index') }}">Kembali ke Daftar</a> [cite: 442]
@endsection