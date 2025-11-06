@extends('layout.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Daftar Dosen</h3>
    <a href="{{ route('dosen.create') }}" class="btn btn-primary">+ Tambah Dosen</a>
</div>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>NIP</th>
            <th>Nama Lengkap</th>
            <th>Email</th>
            <th>Bidang Keahlian</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($dosens as $index => $dosen)
        <tr>
            <td>{{ $dosens->firstItem() + $index }}</td>
            <td>{{ $dosen->nip }}</td>
            <td>{{ $dosen->nama }}</td>
            <td>{{ $dosen->email }}</td>
            <td>{{ $dosen->bidang_keahlian }}</td>
            <td>
                <a href="{{ route('dosen.edit', $dosen->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('dosen.destroy', $dosen->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">Belum ada data dosen.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-center">
    {{ $dosens->links() }}
</div>
@endsection