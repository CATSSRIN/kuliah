<!DOCTYPE html>
<head>
    <title>Daftar Semua Buku</title>
</head>
<body>
    <h1>Daftar Semua Buku</h1>
    <ul>
        @foreach ($books as $id => $book)
            <li>
                <a href="/books/{{ $id }}">
                    {{ $book['judul'] }} - {{ $book['penulis'] }} (Status: {{ $book['status'] }})
                </a>
            </li>
        @endforeach
    </ul>
    <a href="/books/available">Lihat Buku yang Tersedia</a>
</body>
</html>