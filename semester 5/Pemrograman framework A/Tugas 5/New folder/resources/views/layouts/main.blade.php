<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>@yield('title', 'Inventaris')</title>
  <style>
    body{font-family:sans-serif}
    table{border-collapse:collapse;width:100%}
    th,td{border:1px solid #ccc;padding:8px}
    th{background:#f4f4f4}
    .success{background:#e6ffed;padding:10px;border:1px solid #8fdf9f;margin-bottom:15px}
    .container{width:960px;margin:0 auto}
    nav a{padding:5px 10px;text-decoration:none}
    input[type=text], input[type=number], input[type=date]{width:100%;padding:8px;box-sizing:border-box}
    div{margin-bottom:10px}
    button{padding:8px 12px;background:#007bff;color:white;border:none;cursor:pointer}
    .error-list{color:red;background:#ffebeb;border:1px solid #ffc8c8;padding:10px;list-style-position:inside}
  </style>
</head>
<body>
  <nav>
    <a href="{{ route('barang.index') }}">Daftar Barang</a> |
    <a href="{{ route('barang.create') }}">Tambah Barang</a>
  </nav>
  <hr>
  <div class="container">
    @if(session('success'))
      <div class="success">{{ session('success') }}</div>
    @endif
    
    @if($errors->any())
      <div class="error-list">
        <strong>Terjadi Kesalahan:</strong>
        <ul>
          @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
        </ul>
      </div>
    @endif

    @yield('content')
  </div>
</body>
</html>