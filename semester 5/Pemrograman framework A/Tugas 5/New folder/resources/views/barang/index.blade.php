@extends('layouts.main')
@section('title','Daftar Barang')
@section('content')
<h1>Daftar Barang</h1>

<form method="GET" action="{{ route('barang.index') }}">
  <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari kode/nama barang...">
  <button type_submit>Search</button>
</form>
<br>
<table>
  <thead>
    <tr>
      <th>No</th>
      <th>Kode Barang</th>
      <th>Nama Barang</th>
      <th>Kategori</th>
      <th>Jumlah</th>
      <th>Kondisi</th>
      <th>Tgl. Masuk</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse($barangs as $brg)
      <tr>
        <td>{{ $loop->iteration + (($barangs->currentPage()-1) * $barangs->perPage()) }}</td>
        <td>{{ $brg->kode_barang }}</td>
        <td>{{ $brg->nama_barang }}</td>
        <td>{{ $brg->kategori }}</td>
        <td>{{ $brg->jumlah }}</td>
        <td>{{ $brg->kondisi }}</td>
        <td>{{ $brg->tanggal_masuk }}</td>
        <td>
          <a href="{{ route('barang.show', $brg->id) }}">Detail</a> |
          <a href="{{ route('barang.edit', $brg->id) }}">Edit</a> |
          <form action="{{ route('barang.destroy', $brg->id) }}" method="POST" style="display:inline">
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Yakin mau hapus data ini?')" style="background:red">Hapus</button>
          </form>
        </td>
      </tr>
    @empty
      <tr><td colspan="8">Belum ada data barang.</td></tr>
    @endforelse
  </tbody>
</table>

<div>
  {{ $barangs->appends(['q'=>$q ?? ''])->links() }}
</div>
@endsection