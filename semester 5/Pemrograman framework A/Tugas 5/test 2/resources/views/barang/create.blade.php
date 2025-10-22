@extends('layouts.main')

@section('title', 'Tambah Barang Baru')

@section('content')
    <h1>Tambah Barang Baru</h1>

    {{-- Menampilkan Error Validasi [cite: 367-373] --}}
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

    <form method="POST" action="{{ route('barang.store') }}"> [cite: 374]
        @csrf
        <div>
            <label>Kode Barang</label><br>
            <input type="text" name="kode_barang" value="{{ old('kode_barang') }}">
        </div>
        <div>
            <label>Nama Barang</label><br>
            <input type="text" name="nama_barang" value="{{ old('nama_barang') }}">
        </div>
        <div>
            <label>Kategori</label><br>
            <input type="text" name="kategori" value="{{ old('kategori') }}">
        </div>
        <div>
            <label>Jumlah</label><br>
            <input type="number" name="jumlah" value="{{ old('jumlah') }}">
        </div>
         <div>
            <label>Kondisi</label><br>
            <input type="text" name="kondisi" value="{{ old('kondisi') }}">
        </div>
        <div>
            <label>Tanggal Masuk</label><br>
            <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}">
        </div>
        <br>
        <button type="submit">Simpan</button>
    </form>
@endsection