<?php
// --- PENGATURAN KONEKSI DATABASE ---
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = ''; // GANTI DENGAN PASSWORD DATABASE ANDA
$dbName = 'indodax_data';

// Buat koneksi ke database
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Inisialisasi variabel untuk pesan feedback
$message = '';

// --- LOGIKA UNTUK MENGAMBIL DATA JIKA TOMBOL DITEKAN ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ambil_data'])) {
    
    // URL API Indodax
    $apiUrl = "https://indodax.com/api/tickers";
    
    // Menggunakan cURL untuk mengambil data API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200 && $response) {
        $data = json_decode($response, true);
        $tickers = $data['tickers'] ?? [];

        if (!empty($tickers)) {
            // Siapkan SQL statement untuk mencegah SQL Injection
            $sql = "INSERT INTO tickers (pair, high, low, vol_base, vol_quote, last, buy, sell, server_time)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            $rowCount = 0;
            foreach ($tickers as $pair => $ticker) {
                // Mendapatkan volume base dan quote secara dinamis
                $pair_keys = explode('_', $pair);
                $vol_base = $ticker['vol_' . $pair_keys[0]];
                $vol_quote = $ticker['vol_' . $pair_keys[1]];

                // Bind parameter ke statement
                $stmt->bind_param(
                    "sdddddddi",
                    $pair,
                    $ticker['high'],
                    $ticker['low'],
                    $vol_base,
                    $vol_quote,
                    $ticker['last'],
                    $ticker['buy'],
                    $ticker['sell'],
                    $ticker['server_time']
                );

                // Eksekusi statement
                if ($stmt->execute()) {
                    $rowCount++;
                }
            }
            $message = "Berhasil! Sebanyak $rowCount data ticker baru telah disimpan.";
            $stmt->close();
        } else {
            $message = "Gagal memproses data dari API. Format tidak sesuai.";
        }
    } else {
        $message = "Gagal mengambil data dari API Indodax. HTTP Code: " . $httpCode;
    }
}

// --- LOGIKA UNTUK MEMBACA SEMUA DATA DARI TABEL UNTUK DITAMPILKAN ---
$ticker_rows = [];
$result = $conn->query("SELECT pair, high, low, last, buy, sell, fetch_time FROM tickers ORDER BY fetch_time DESC");
if ($result && $result->num_rows > 0) {
    $ticker_rows = $result->fetch_all(MYSQLI_ASSOC);
}

// Tutup koneksi setelah semua operasi selesai
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indodax Ticker Data</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f4f7f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            border-bottom: 2px solid #eeeeee;
            padding-bottom: 10px;
        }
        .controls {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-ambil {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-ambil:hover {
            background-color: #2980b9;
        }
        .message {
            text-align: center;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            background-color: #eafaf1;
            color: #2d6a4f;
            font-weight: bold;
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #ecf0f1;
            color: #34495e;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        td {
            text-align: right;
        }
        td:first-child {
            text-align: left;
            font-weight: bold;
            color: #2980b9;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>📊 Indodax Market Tickers</h1>
        
        <div class="controls">
            <form method="post">
                <button type="submit" name="ambil_data" class="btn-ambil">🚀 Ambil Data Terbaru</button>
            </form>
        </div>

        <?php if (!empty($message)): ?>
            <div class="message">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Pair</th>
                        <th>Harga Terakhir (IDR)</th>
                        <th>Beli (IDR)</th>
                        <th>Jual (IDR)</th>
                        <th>Tertinggi 24 Jam</th>
                        <th>Terendah 24 Jam</th>
                        <th>Waktu Ambil Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($ticker_rows)): ?>
                        <?php foreach ($ticker_rows as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(strtoupper(str_replace('_', '/', $row['pair']))); ?></td>
                                <td><?php echo number_format($row['last'], 0, ',', '.'); ?></td>
                                <td><?php echo number_format($row['buy'], 0, ',', '.'); ?></td>
                                <td><?php echo number_format($row['sell'], 0, ',', '.'); ?></td>
                                <td><?php echo number_format($row['high'], 0, ',', '.'); ?></td>
                                <td><?php echo number_format($row['low'], 0, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($row['fetch_time']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">Belum ada data di database. Silakan klik tombol "Ambil Data Terbaru".</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>