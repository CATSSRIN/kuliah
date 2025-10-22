@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Detail Penulis: {{ $author->name }}</h5>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <strong>Nama:</strong>
            <p>{{ $author->name }}</p>
        </div>

        <hr>

        <h5>Buku-buku karya {{ $author->name }}:</h5>
        @if ($author->books->count() > 0)
            <ul>
                @foreach ($author->books as $book)
                    <li>{{ $book->title }}</li>
                @endforeach
            </ul>
        @else
            <p>Penulis ini belum memiliki buku yang terdaftar.</p>
        @endif

        <a href="{{ route('authors.index') }}" class="btn btn-primary mt-3">Kembali ke Daftar</a>
    </div>
</div>
@endsection