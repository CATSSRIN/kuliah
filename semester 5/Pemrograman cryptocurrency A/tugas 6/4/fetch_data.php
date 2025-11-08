<?php
// fetch_data.php

// Set header agar output berupa teks biasa (untuk debugging)
header('Content-Type: text/plain');

// 1. Sertakan file konfigurasi database
require_once 'db_config.php';

$apiUrl = 'https://api.vindax.com/api/v1/ticker/24hr';
echo "Memulai proses pengambilan data dari API...\n";

// 2. Ambil data dari API
$json_data = @file_get_contents($apiUrl);

if ($json_data === false) {
    die("ERROR: Gagal mengambil data dari API Vindax.");
}

// 3. Decode data JSON
// API Vindax mengembalikan array dari objek ticker
$tickers = json_decode($json_data, true);

if (!is_array($tickers)) {
    die("ERROR: Format data API tidak terduga.");
}

// 4. Siapkan SQL Statement (Gunakan ON DUPLICATE KEY UPDATE)
// Ini akan memasukkan data baru jika simbol belum ada, 
// atau memperbarui data yang ada jika simbol sudah ada.
$sql = "INSERT INTO tickers (symbol, last_price, high_price, low_price, volume, price_change_percent) 
        VALUES (:symbol, :last_price, :high_price, :low_price, :volume, :price_change_percent)
        ON DUPLICATE KEY UPDATE
            last_price = VALUES(last_price),
            high_price = VALUES(high_price),
            low_price = VALUES(low_price),
            volume = VALUES(volume),
            price_change_percent = VALUES(price_change_percent)";

$stmt = $pdo->prepare($sql);

$total_rows = 0;

try {
    // 5. Mulai transaksi database
    $pdo->beginTransaction();

    // 6. Looping setiap ticker dan masukkan/update ke DB
    foreach ($tickers as $ticker) {
        // Kita perlu mencocokkan nama field dari API
        // Berdasarkan API serupa, field-nya mungkin seperti ini:
        // 'symbol', 'lastPrice', 'highPrice', 'lowPrice', 'volume', 'priceChangePercent'
        // Sesuaikan nama field di bawah ini jika diperlukan
        
        $params = [
            ':symbol'               => $ticker['symbol'],
            ':last_price'           => $ticker['lastPrice'],
            ':high_price'           => $ticker['highPrice'],
            ':low_price'            => $ticker['lowPrice'],
            ':volume'               => $ticker['volume'],
            ':price_change_percent' => $ticker['priceChangePercent']
        ];
        
        $stmt->execute($params);
        $total_rows++;
    }

    // 7. Commit transaksi
    $pdo->commit();
    
    echo "SUKSES: " . $total_rows . " data ticker berhasil disimpan/diperbarui.";

} catch (Exception $e) {
    // Rollback transaksi jika ada error
    $pdo->rollBack();
    die("ERROR: Gagal menyimpan data ke database. " . $e->getMessage());
}

?>