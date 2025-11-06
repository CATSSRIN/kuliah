@extends('layout.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Daftar Proyek</h3>
    <a href="{{ route('proyek.create') }}" class="btn btn-primary">+ Tambah Proyek</a>
</div>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Judul Proyek</th>
            <th>Mahasiswa (NIM)</th>
            <th>Dosen Pembimbing</th>
            <th>Status</th>
            <th>Dokumen</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($proyeks as $index => $proyek)
        <tr>
            <td>{{ $proyeks->firstItem() + $index }}</td>
            <td>{{ $proyek->judul }}</td>
            <td>{{ $proyek->mahasiswa->nama ?? 'N/A' }} ({{ $proyek->mahasiswa->nim ?? 'N/A' }})</td>
            <td>{{ $proyek->dosen->nama ?? 'N/A' }}</td>
            <td><span class="badge bg-info">{{ $proyek->status }}</span></td>
            <td>
                @if ($proyek->dokumen)
                    <a href="{{ asset('storage/'.$proyek->dokumen) }}" target="_blank">Lihat</a>
                @else
                    <span class="text-muted">Tidak ada</span>
                @endif
            </td>
            <td>
                <a href="{{ route('proyek.edit', $proyek->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('proyek.destroy', $proyek->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">Belum ada data proyek.</td>
        </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-center">
    {{ $proyeks->links() }}
</div>
@endsection