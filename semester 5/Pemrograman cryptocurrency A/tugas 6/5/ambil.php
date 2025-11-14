<?php
// ambil.php

// 1. Masukkan file koneksi database
require_once 'db.php';

// 2. Tentukan simbol crypto yang ingin diambil.
// Kita gunakan parameter GET (cth: ambil.php?symbol=BTCUSDT)
// Jika tidak ada parameter, kita gunakan BTCUSDT sebagai default.
$symbol = $_GET['symbol'] ?? 'BTCUSDT';

// 3. Panggil API Binance
$apiUrl = 'https://api.binance.com/api/v3/ticker/price?symbol=' . urlencode($symbol);
echo "Memanggil API: $apiUrl ...\n";

// Menggunakan file_get_contents (pastikan allow_url_fopen diaktifkan di server Anda)
$json_data = @file_get_contents($apiUrl);

if ($json_data === FALSE) {
    die("Error: Tidak dapat mengambil data dari API Binance. Cek simbol atau koneksi internet.\n");
}

$data = json_decode($json_data, true);

if (!isset($data['price'])) {
     die("Error: Respon API tidak valid atau simbol tidak ditemukan.\n");
}

// 4. Ekstrak data dan siapkan nama tabel
$price = $data['price'];
$symbolName = $data['symbol'];

// Sanitasi nama simbol untuk dijadikan nama tabel (hanya huruf dan angka, ubah ke lowercase)
$tableName = strtolower(preg_replace("/[^a-zA-Z0-9]/", "", $symbolName));

echo "Data diterima: Simbol $symbolName, Harga $price. Menyimpan ke tabel `$tableName`...\n";


try {
    // 5. Buat Tabel JIKA BELUM ADA (CREATE TABLE IF NOT EXISTS)
    // Ini adalah bagian kunci dari permintaan Anda.
    $sqlCreateTable = "
        CREATE TABLE IF NOT EXISTS `{$tableName}` (
            id INT AUTO_INCREMENT PRIMARY KEY,
            price DECIMAL(20, 8) NOT NULL,
            fetch_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    ";
    
    // Kita gunakan exec() untuk DDL (Data Definition Language)
    $pdo->exec($sqlCreateTable);
    echo "Pengecekan tabel `$tableName` sukses (dibuat jika belum ada).\n";


    // 6. Masukkan Data (INSERT)
    // Kita gunakan prepared statement untuk keamanan dan efisiensi
    $sqlInsert = "INSERT INTO `{$tableName}` (price) VALUES (:price)";
    
    $stmt = $pdo->prepare($sqlInsert);
    
    // Bind parameter dan eksekusi
    $stmt->execute(['price' => $price]);

    echo "Sukses! Data harga terbaru untuk $symbolName telah disimpan ke database.\n";

} catch (\PDOException $e) {
    // Tangani jika ada error database
    die("Database error: " . $e->getMessage() . "\n");
}
?>