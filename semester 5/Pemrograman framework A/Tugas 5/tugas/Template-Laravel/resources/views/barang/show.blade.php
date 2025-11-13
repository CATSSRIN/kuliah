@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Detail Barang</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Kode Barang:</strong>
                </div>
                <div class="col-md-9">
                    {{ $barang->kode_barang }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Nama Barang:</strong>
                </div>
                <div class="col-md-9">
                    {{ $barang->nama_barang }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Kategori:</strong>
                </div>
                <div class="col-md-9">
                    {{ $barang->kategori }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Jumlah:</strong>
                </div>
                <div class="col-md-9">
                    {{ $barang->jumlah }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Tanggal Masuk:</strong>
                </div>
                <div class="col-md-9">
                    {{ $barang->tanggal_masuk->format('d-m-Y') }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Dibuat:</strong>
                </div>
                <div class="col-md-9">
                    {{ $barang->created_at->format('d-m-Y H:i') }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Diubah:</strong>
                </div>
                <div class="col-md-9">
                    {{ $barang->updated_at->format('d-m-Y H:i') }}
                </div>
            </div>

            <div class="d-grid gap-2 d-sm-flex justify-content-sm-end">
                <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection