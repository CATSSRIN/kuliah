<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Aplikasi')</title> {{-- [cite: 284, 285] --}}
    <style>
        table { border-collapse: collapse; width: 100%; } /* [cite: 287] */
        th, td { border: 1px solid #ccc; padding: 8px; } /* [cite: 288] */
        .success { background: #e6ffed; padding: 8px; border: 1px solid #8fdf9f; } /* [cite: 289] */
    </style>
</head>
<body>
    <nav>
        {{-- Ini adalah contoh navigasi dari slide, Anda bisa menambah/menggantinya --}}
        <a href="{{ route('mahasiswa.index') }}">Mahasiswa</a> | {{-- [cite: 294] --}}
        <a href="{{ route('mahasiswa.create') }}">Tambah Mahasiswa</a> {{-- [cite: 295] --}}
        
        {{-- TAMBAHKAN INI UNTUK NAVIGASI BARANG --}}
         | <a href="{{ route('barang.index') }}">Barang</a>
         | <a href="{{ route('barang.create') }}">Tambah Barang</a>
    </nav>
    <hr>
    <div class="container">
        [cite_start]{{-- Untuk menampilkan pesan sukses [cite: 299-301] --}}
        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        @yield('content') {{-- [cite: 302] --}}
    </div>
</body>
</html>