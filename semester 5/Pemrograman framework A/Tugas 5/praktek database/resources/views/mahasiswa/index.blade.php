@extends('layouts.main')
@section('title','Daftar Mahasiswa')
@section('content')
<h1>Daftar Mahasiswa</h1>

<form method="GET" action="{{ route('mahasiswa.index') }}">
  <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari nama...">
  <button>Search</button>
</form>

<table>
  <thead>
    <tr><th>No</th><th>NPM</th><th>Nama</th><th>Prodi</th><th>Aksi</th></tr>
  </thead>
  <tbody>
    @forelse($mahasiswas as $m)
      <tr>
        <td>{{ $loop->iteration + (($mahasiswas->currentPage()-1) * $mahasiswas->perPage()) }}</td>
        <td>{{ $m->npm}}</td>
        <td>{{ $m->nama }}</td>
        <td>{{ $m->prodi }}</td>
        <td>
          <a href="{{ route('mahasiswa.show', $m->id) }}">Detail</a> |
          <a href="{{ route('mahasiswa.edit', $m->id) }}">Edit</a> |
          <form action="{{ route('mahasiswa.destroy', $m->id) }}" method="POST" style="display:inline">
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Yakin mau hapus?')">Hapus</button>
          </form>
        </td>
      </tr>
    @empty
      <tr><td colspan="5">Belum ada data.</td></tr>
    @endforelse
  </tbody>
</table>

<div>
  {{ $mahasiswas->appends(['q'=>$q ?? ''])->links() }}
</div>
@endsection
