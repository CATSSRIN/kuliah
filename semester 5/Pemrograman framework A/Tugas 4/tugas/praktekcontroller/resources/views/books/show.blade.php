<!DOCTYPE html>
<head>
    <title>Detail Buku</title>
</head>
<body>
    <h1>Detail Buku</h1>
    <ul>
        <li>Judul: {{ $book['judul'] }}</li>
        <li>Penulis: {{ $book['penulis'] }}</li>
        <li>Status: {{ $book['status'] }}</li>
    </ul>
    <a href="/books">Kembali ke Daftar Buku</a>
</body>
</html>