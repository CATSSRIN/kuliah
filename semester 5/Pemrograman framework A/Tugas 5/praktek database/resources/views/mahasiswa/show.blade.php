@extends('layouts.main')
@section('title','Detail Mahasiswa')
@section('content')
<h1>Detail Mahasiswa</h1>
<p><strong>ID:</strong> {{ $mahasiswa->id }}</p>
<p><strong>NPM:</strong> {{ $mahasiswa->npm}}</p>
<p><strong>Nama:</strong> {{ $mahasiswa->nama }}</p>
<p><strong>Prodi:</strong> {{ $mahasiswa->prodi }}</p>
<a href="{{ route('mahasiswa.index') }}">Kembali</a>
@endsection
