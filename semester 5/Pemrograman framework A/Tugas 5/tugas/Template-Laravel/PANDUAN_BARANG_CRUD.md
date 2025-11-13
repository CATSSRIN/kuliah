# Panduan CRUD Barang

## Deskripsi Aplikasi
Aplikasi CRUD Barang adalah sistem manajemen inventaris barang kantor yang dibangun menggunakan Laravel Framework. Sistem ini memungkinkan pengguna untuk membuat, membaca, memperbarui, dan menghapus data barang.

## Struktur File yang Dibuat

### 1. Model
- **File**: `app/Models/Barang.php`
- **Deskripsi**: Model yang merepresentasikan tabel barangs
- **Fillable Attributes**: kode_barang, nama_barang, kategori, jumlah, tanggal_masuk

### 2. Migration
- **File**: `database/migrations/2025_11_11_181112_create_barangs_table.php`
- **Deskripsi**: File migrasi untuk membuat tabel barangs
- **Kolom**:
  - id (auto-increment)
  - kode_barang (unique string)
  - nama_barang (string)
  - kategori (string)
  - jumlah (integer)
  - tanggal_masuk (date)
  - timestamps (created_at, updated_at)

### 3. Controller
- **File**: `app/Http/Controllers/BarangController.php`
- **Methods**:
  - `index()` - Menampilkan daftar barang dengan fitur search dan pagination
  - `create()` - Menampilkan form untuk membuat barang baru
  - `store()` - Menyimpan data barang baru ke database
  - `show()` - Menampilkan detail barang tertentu
  - `edit()` - Menampilkan form untuk edit barang
  - `update()` - Memperbarui data barang di database
  - `destroy()` - Menghapus barang dari database

### 4. Routes
- **File**: `routes/web.php`
- **Routes**:
  - GET /barang -> index
  - GET /barang/create -> create
  - POST /barang -> store
  - GET /barang/{barang} -> show
  - GET /barang/{barang}/edit -> edit
  - PUT /barang/{barang} -> update
  - DELETE /barang/{barang} -> destroy

### 5. Views
- **Folder**: `resources/views/barang/`
- **Files**:
  - `index.blade.php` - Halaman daftar barang
  - `create.blade.php` - Form tambah barang baru
  - `edit.blade.php` - Form edit barang
  - `show.blade.php` - Halaman detail barang

## Fitur Utama

### 1. List Barang (Index)
- Menampilkan tabel daftar semua barang
- Fitur search berdasarkan nama barang
- Pagination 10 item per halaman
- Tombol Detail, Edit, dan Hapus untuk setiap barang
- Tombol Tambah Barang untuk membuat data baru

### 2. Tambah Barang (Create)
- Form input untuk kode_barang (unique)
- Form input untuk nama_barang
- Form input untuk kategori
- Form input untuk jumlah (integer minimum 1)
- Form input untuk tanggal_masuk
- Validasi lengkap untuk semua field
- Tombol Simpan dan Batal

### 3. Edit Barang (Edit)
- Form edit yang sudah ter-fill dengan data barang
- Validasi yang sama seperti create
- Tombol Simpan dan Batal
- Unique constraint pada kode_barang untuk barang lain

### 4. Detail Barang (Show)
- Menampilkan semua detail barang
- Menampilkan tanggal dibuat dan diubah
- Tombol Edit untuk mengubah data
- Tombol Kembali ke daftar barang

## Setup & Instalasi

### 1. Database Migration
```bash
php artisan migrate
```

### 2. Jalankan Server
```bash
php artisan serve
```

### 3. Akses Aplikasi
Buka browser dan akses: `http://localhost:8000/barang`

## Validasi Data

### Create & Update
- **kode_barang**: Required, unique, string
- **nama_barang**: Required, string
- **kategori**: Required, string
- **jumlah**: Required, integer, minimum 1
- **tanggal_masuk**: Required, date format (Y-m-d)

## Teknologi yang Digunakan
- Laravel 11
- PHP 8.x
- MySQL/MariaDB
- Bootstrap 5 (untuk styling)
- Blade Templating Engine

## Note Penting
- Semua field form bersifat required
- Kode_barang harus unik, tidak boleh ada duplikat
- Pagination otomatis membagi data per 10 item
- Fitur search real-time pada index page
- Flash message akan muncul setelah operasi create, update, delete

## Troubleshooting

### Database tidak termigrasi
- Pastikan sudah menjalankan `php artisan migrate`
- Cek konfigurasi database di `.env`

### Error saat submit form
- Pastikan semua field terisi dengan benar
- Cek error message yang ditampilkan
- Pastikan kode_barang tidak ada duplikat

## Lisensi
Aplikasi ini merupakan bagian dari Tugas 5 Pemrograman Framework
