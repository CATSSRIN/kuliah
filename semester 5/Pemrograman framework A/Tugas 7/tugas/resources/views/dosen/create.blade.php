@extends('layout.app')

@section('content')
<h3>Tambah Dosen Baru</h3>
<hr>
<form action="{{ route('dosen.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="nama" class="form-label">Nama Lengkap</label>
        <input type="text" name="nama" class="form-control" value="{{ old('nama') }}">
        @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="mb-3">
        <label for="nip" class="form-label">NIP</label>
        <input type="text" name="nip" class="form-control" value="{{ old('nip') }}">
        @error('nip') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="mb-3">
        <label for="bidang_keahlian" class="form-label">Bidang Keahlian</label>
        <input type="text" name="bidang_keahlian" class="form-control" value="{{ old('bidang_keahlian') }}">
        @error('bidang_keahlian') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="{{ route('dosen.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection