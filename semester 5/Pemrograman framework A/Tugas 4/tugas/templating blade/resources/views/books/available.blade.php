<!DOCTYPE html>
<head>
    <title>Buku yang Tersedia</title>
</head>
<body>
    <h1>Daftar Buku yang Tersedia</h1>
    <ul>
        @foreach ($availableBooks as $id => $book)
            <li>
                <a href="/books/{{ $id }}">
                    {{ $book['judul'] }} - {{ $book['penulis'] }}
                </a>
            </li>
        @endforeach
    </ul>
    <a href="/books">Kembali ke Daftar Buku</a>
</body>
</html>