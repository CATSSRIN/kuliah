@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Detail Buku</h5>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <strong>Judul:</strong>
            <p>{{ $book->title }}</p>
        </div>
        <div class="mb-3">
            <strong>Penulis:</strong>
            <p>{{ $book->author->name }}</p>
        </div>
        <div class="mb-3">
            <strong>Deskripsi:</strong>
            <p>{{ $book->description ?? 'Tidak ada deskripsi.' }}</p>
        </div>
        <div class="mb-3">
            <strong>Dibuat Pada:</strong>
            <p>{{ $book->created_at->format('d F Y, H:i') }}</p>
        </div>
        <a href="{{ route('books.index') }}" class="btn btn-primary">Kembali ke Daftar</a>
    </div>
</div>
@endsection