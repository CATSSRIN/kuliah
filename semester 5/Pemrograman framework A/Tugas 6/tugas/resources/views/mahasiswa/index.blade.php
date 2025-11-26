@extends('layout.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Daftar Mahasiswa</h3>
    @if(Auth::user()->isAdmin())
    <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary">+ Tambah Mahasiswa</a>
    @endif
</div>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Foto</th>
            <th>NIM</th>
            <th>Nama Lengkap</th>
            <th>Email</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($mahasiswas as $index => $mhs)
        <tr>
            <td>{{ $mahasiswas->firstItem() + $index }}</td>
            <td>
                @if ($mhs->foto)
                    <img src="{{ asset('storage/'.$mhs->foto) }}" width="60" class="rounded">
                @else
                    <span class="text-muted">Tidak ada</span>
                @endif
            </td>
            <td>{{ $mhs->nim }}</td>
            <td>{{ $mhs->nama }}</td>
            <td>{{ $mhs->email }}</td>
            <td>
                <a href="{{ route('mahasiswa.show', $mhs->id) }}" class="btn btn-sm btn-info">Detail</a>
                @if(Auth::user()->isAdmin())
                <a href="{{ route('mahasiswa.edit', $mhs->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('mahasiswa.destroy', $mhs->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">Belum ada data mahasiswa.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-center">
    {{ $mahasiswas->links() }}
</div>
@endsection