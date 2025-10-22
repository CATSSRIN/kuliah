@extends('layouts.main')
@section('title','Tambah Mahasiswa')
@section('content')
<h1>Tambah Mahasiswa</h1>

@if($errors->any())
  <div style="color:red">
    <ul>
      @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
    </ul>
  </div>
@endif

<form method="POST" action="{{ route('mahasiswa.store') }}">
  @csrf
  <div>
    <label>NPM</label>
    <input type="text" name="npm" value="{{ old('npm') }}">
  </div>
  <div>
    <label>Nama</label>
    <input type="text" name="nama" value="{{ old('nama') }}">
  </div>
  <div>
    <label>Prodi</label>
    <input type="text" name="prodi" value="{{ old('prodi') }}">
  </div>
  <button type="submit">Simpan</button>
</form>
@endsection
