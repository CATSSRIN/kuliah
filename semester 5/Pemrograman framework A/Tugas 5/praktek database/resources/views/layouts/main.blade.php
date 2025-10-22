

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>@yield('title', 'Aplikasi')</title>
  <style>
    table{border-collapse:collapse;width:100%}
    th,td{border:1px solid #ccc;padding:8px}
    .success{background:#e6ffed;padding:8px;border:1px solid #8fdf9f}
  </style>
</head>
<body>
  <nav>
    <a href="{{ route('mahasiswa.index') }}">Mahasiswa</a> |
    <a href="{{ route('mahasiswa.create') }}">Tambah Mahasiswa</a>
  </nav>
  <hr>
  <div class="container">
    @if(session('success'))
      <div class="success">{{ session('success') }}</div>
    @endif

    @yield('content')
  </div>
</body>
</html>


