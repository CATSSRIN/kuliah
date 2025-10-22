@extends('layouts.main')
@section('title','Edit Mahasiswa')
@section('content')
<h1>Edit Mahasiswa</h1>

@if($errors->any())
  <div style="color:red">
    <ul>
      @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
    </ul>
  </div>
@endif

<form method="POST" action="{{ route('mahasiswa.update', $mahasiswa->id) }}">
  @csrf
  @method('PUT')
  <div><label>NPM</label>
    <input type="text" name="npm" value="{{ old('npm', $mahasiswa->npm) }}"></div>
  <div><label>Nama</label>
    <input type="text" name="nama" value="{{ old('nama', $mahasiswa->nama) }}"></div>
  <div><label>Prodi</label>
    <input type="text" name="prodi" value="{{ old('prodi', $mahasiswa->prodi) }}"></div>
  <button type="submit">Update</button>
</form>
@endsection
