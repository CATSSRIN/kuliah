@extends('layout.app')

@section('content')
<h3>Edit Data Dosen</h3>
<hr>
<form action="{{ route('dosen.update', $dosen->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="nama" class="form-label">Nama Lengkap</label>
        <input type="text" name="nama" class="form-control" value="{{ old('nama', $dosen->nama) }}">
        @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="mb-3">
        <label for="nip" class="form-label">NIP</label>
        <input type="text" name="nip" class="form-control" value="{{ old('nip', $dosen->nip) }}">
        @error('nip') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $dosen->email) }}">
        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="mb-3">
        <label for="bidang_keahlian" class="form-label">Bidang Keahlian</label>
        <input type="text" name="bidang_keahlian" class="form-control" value="{{ old('bidang_keahlian', $dosen->bidang_keahlian) }}">
        @error('bidang_keahlian') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <button type="submit" class="btn btn-success">Perbarui</button>
    <a href="{{ route('dosen.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection