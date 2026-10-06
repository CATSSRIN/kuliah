# 🤖 Sistem Timer & Papan Skor Lomba Robotika (Real-Time Race Stopwatch)

Aplikasi web pencatat waktu balapan / lomba robotika yang tersinkronisasi secara real-time antara **Admin/Juri** dan **Peserta/Tim**.

---

## 🚀 Fitur Utama

1. **Login Sederhana Peserta**:
   - Peserta hanya perlu memasukkan **Nama Tim / Username** tanpa perlu register atau password yang rumit.
2. **Master Control Timer Admin**:
   - Admin memulai timer (bisa dengan **Hitungan Mundur 3-2-1** atau **Mulai Langsung**).
   - Seluruh layar peserta otomatis tersinkronisasi menampilkan timer stopwatch digital yang berjalan presisi.
3. **Pencatatan Waktu Mandiri Tiap Peserta**:
   - Peserta memiliki tombol **STOP** raksasa yang responsif (juga didukung shortcut **Tombol Spasi** & getaran haptic di HP).
   - Saat peserta menekan STOP, waktu finish mereka langsung terkunci dan dikirim secara instan ke layar Admin.
4. **Papan Skor & Leaderboard Real-Time (Layar Admin)**:
   - Menampilkan urutan ranking peserta secara otomatis dari waktu tercepat (🥇 Juara 1, 🥈 Juara 2, 🥉 Juara 3).
   - Fitur **Penalti (+/- detik)** jika ada pelanggaran lintasan.
   - Perhitungan selisih waktu otomatis terhadap pemegang waktu tercepat (*Leader*).
   - Fitur **Reset Per Peserta** atau **Reset Ronde Baru**.
   - Fitur **Export Hasil ke File CSV / Excel** & **Cetak / PDF**.
5. **Efek Audio Synthesizer**:
   - Dilengkapi suara buzzer countdown, start buzzer, dan chime finish bawaan Web Audio API (tidak butuh file eksternal).
6. **Multi-Device / Wi-Fi Support**:
   - Bisa diakses bersamaan melalui Laptop Admin dan Smartphone/Laptop para peserta di jaringan Wi-Fi/LAN yang sama.

---

## 🛠️ Cara Menjalankan Aplikasi

1. Buka terminal di folder ini:
   ```bash
   npm install
   ```
2. Jalankan server:
   ```bash
   npm start
   ```
3. Akses aplikasi:
   - **Di Laptop ini (Local):** `http://localhost:3000`
   - **Di HP/Laptop Peserta (Satu Wi-Fi):** Buka alamat IP yang tertera di terminal / layar admin (contoh: `http://192.168.1.X:3000`)

---

## 🔑 Informasi Akun Admin
- Pilih tab **Juri / Admin** pada halaman utama.
- **Default Password Admin:** `admin123` (atau `admin`)
