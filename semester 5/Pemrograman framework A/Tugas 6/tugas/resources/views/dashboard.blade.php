@extends('layout.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h2>Dashboard</h2>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Selamat Datang, {{ Auth::user()->name }}!</h5>
                <p class="card-text">
                    Anda login sebagai 
                    @if(Auth::user()->isAdmin())
                        <span class="badge bg-danger">Admin</span>
                    @else
                        <span class="badge bg-secondary">User</span>
                    @endif
                </p>
                <hr>
                @if(Auth::user()->isAdmin())
                <p>Sebagai Admin, Anda memiliki akses penuh untuk:</p>
                <ul>
                    <li>Melihat semua data mahasiswa</li>
                    <li>Menambah data mahasiswa baru</li>
                    <li>Mengubah data mahasiswa</li>
                    <li>Menghapus data mahasiswa</li>
                </ul>
                @else
                <p>Sebagai User, Anda dapat:</p>
                <ul>
                    <li>Melihat daftar mahasiswa</li>
                    <li>Melihat detail mahasiswa</li>
                </ul>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Mahasiswa</h5>
                <p class="card-text">Kelola data mahasiswa</p>
                <a href="{{ route('mahasiswa.index') }}" class="btn btn-light">Lihat Data</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Dosen</h5>
                <p class="card-text">Kelola data dosen</p>
                <a href="{{ route('dosen.index') }}" class="btn btn-light">Lihat Data</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Proyek</h5>
                <p class="card-text">Kelola data proyek</p>
                <a href="{{ route('proyek.index') }}" class="btn btn-light">Lihat Data</a>
            </div>
        </div>
    </div>
</div>
@endsection
