@extends('layout.app')

@section('content')
<h3>Tambah Proyek Baru</h3>
<hr>
<form action="{{ route('proyek.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="judul" class="form-label">Judul Proyek</label>
        <input type="text" name="judul" class="form-control" value="{{ old('judul') }}">
        @error('judul') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control">{{ old('deskripsi') }}</textarea>
        @error('deskripsi') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="mb-3">
        <label for="mahasiswa_id" class="form-label">Mahasiswa</label>
        <select name="mahasiswa_id" class="form-select">
            <option value="">-- Pilih Mahasiswa --</option>
            @foreach ($mahasiswas as $mhs)
                <option value="{{ $mhs->id }}" {{ old('mahasiswa_id') == $mhs->id ? 'selected' : '' }}>
                    {{ $mhs->nama }} ({{ $mhs->nim }})
                </option>
            @endforeach
        </select>
        @error('mahasiswa_id') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="mb-3">
        <label for="dosen_id" class="form-label">Dosen Pembimbing</label>
        <select name="dosen_id" class="form-select">
            <option value="">-- Pilih Dosen --</option>
            @foreach ($dosens as $dosen)
                <option value="{{ $dosen->id }}" {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}>
                    {{ $dosen->nama }}
                </option>
            @endforeach
        </select>
        @error('dosen_id') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</Coption>
        </select>
        @error('status') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="mb-3">
        <label for="dokumen" class="form-label">Dokumen (PDF/DOCX, max 2MB)</label>
        <input type="file" name="dokumen" class="form-control">
        @error('dokumen') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="{{ route('proyek.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection