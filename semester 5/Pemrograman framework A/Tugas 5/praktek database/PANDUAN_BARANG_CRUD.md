@extends('layouts.main')

@section('title', 'Detail Barang')

@section('content')
<h1>Detail Barang</h1>

<p><strong>ID:</strong> {{ $barang->id }}</p>
<p><strong>Kode Barang:</strong> {{ $barang->kode_barang }}</p>
<p><strong>Nama Barang:</strong> {{ $barang->nama_barang }}</p>
<p><strong>Kategori:</strong> {{ $barang->kategori }}</p>
<p><strong>Jumlah:</strong> {{ $barang->jumlah }}</p>
<p><strong>Tanggal Masuk:</strong> {{ $barang->tanggal_masuk }}</p>

<a href="{{ route('barang.index') }}">Kembali</a>
@endsection
