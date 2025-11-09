
<h1>Daftar Mahasiswa</h1>
<ul>
@foreach($mahasiswa as $m)
    <li>
      <a href="{{ route('mahasiswa.show', $m['id']) }}">
        {{ $m['nama'] }} ({{ $m['nim'] }})
      </a>
    </li>
@endforeach
</ul>

