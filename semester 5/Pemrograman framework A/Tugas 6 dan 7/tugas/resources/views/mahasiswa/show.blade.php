@extends('layout.app')

@section('content')
<h3>Detail Mahasiswa</h3>
<hr>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 text-center">
                @if ($mahasiswa->foto)
                    <img src="{{ asset('storage/'.$mahasiswa->foto) }}" class="img-fluid rounded" style="max-width: 200px;">
                @else
                    <div class="bg-light p-5 rounded">
                        <span class="text-muted">Tidak ada foto</span>
                    </div>
                @endif
            </div>
            <div class="col-md-8">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">NIM</th>
                        <td>: {{ $mahasiswa->nim }}</td>
                    </tr>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td>: {{ $mahasiswa->nama }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>: {{ $mahasiswa->email }}</td>
                    </tr>
                    <tr>
                        <th>Dibuat pada</th>
                        <td>: {{ $mahasiswa->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Diperbarui pada</th>
                        <td>: {{ $mahasiswa->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">Kembali</a>
    @if(Auth::user()->isAdmin())
    <a href="{{ route('mahasiswa.edit', $mahasiswa->id) }}" class="btn btn-warning">Edit</a>
    <form action="{{ route('mahasiswa.destroy', $mahasiswa->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger">Hapus</button>
    </form>
    @endif
</div>
@endsection
