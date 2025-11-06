@extends('layout.app')

@section('content')
<h3>Edit Data Mahasiswa</h3>
<hr>
<form action="{{ route('mahasiswa.update', $mahasiswa->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="nama" class="form-label">Nama Lengkap</label>
        <input type="text" name="nama" class="form-control" value="{{ old('nama', $mahasiswa->nama) }}">
        @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="mb-3">
        <label for="nim" class="form-label">NIM</label>
        <input type="text" name="nim" class="form-control" value="{{ old('nim', $mahasiswa->nim) }}">
        @error('nim') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $mahasiswa->email) }}">
        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="mb-3">
        <label for="foto" class="form-label">Foto (JPG/PNG, max 1MB)</label>
        <input type="file" name="foto" class="form-control">
        @if ($mahasiswa->foto)
            <img src="{{ asset('storage/'.$mahasiswa->foto) }}" width="100" class="mt-2 rounded">
            <small class="d-block">Foto saat ini</small>
        @endif
        @error('foto') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <button type="submit" class="btn btn-success">Perbarui</button>
    <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection