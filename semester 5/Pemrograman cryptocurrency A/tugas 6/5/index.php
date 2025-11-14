<?php
// index.php
require_once 'db.php';

// Tentukan simbol mana yang ingin kita TAMPILKAN
// (Misal: index.php?symbol=ETHUSDT)
$symbolToShow = $_GET['symbol'] ?? 'BTCUSDT';

// Sanitasi nama simbol agar cocok dengan nama tabel kita
$tableName = strtolower(preg_replace("/[^a-zA-Z0-9]/", "", $symbolToShow));

$data_history = [];
$error_message = '';

try {
    // Cek dulu apakah tabelnya ada, sebelum mencoba SELECT
    $checkTableStmt = $pdo->query("SHOW TABLES LIKE '{$tableName}'");
    
    if ($checkTableStmt->rowCount() > 0) {
        // Jika tabel ada, ambil 50 data terakhir
        $stmt = $pdo->query("SELECT price, fetch_time FROM `{$tableName}` ORDER BY fetch_time DESC LIMIT 50");
        $data_history = $stmt->fetchAll();
    } else {
        $error_message = "Tabel untuk '$symbolToShow' (`{$tableName}`) belum ada. 
                         Silakan jalankan <code>ambil.php?symbol=$symbolToShow</code> terlebih dahulu.";
    }

} catch (\PDOException $e) {
    $error_message = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Harga Kripto (<?php echo htmlspecialchars($symbolToShow); ?>)</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        table { width: 50%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; }
        .error { color: red; background-color: #ffeeee; padding: 10px; border: 1px solid red; }
    </style>
    <meta http-equiv="refresh" content="60">
</head>
<body>

    <h1>Data Harga <?php echo htmlspecialchars($symbolToShow); ?></h1>
    <p>Data ini diambil dari database dan di-refresh setiap 60 detik.</p>
    <p>
        Lihat simbol lain:
        <a href="index.php?symbol=BTCUSDT">BTCUSDT</a> |
        <a href="index.php?symbol=ETHUSDT">ETHUSDT</a> |
        <a href="index.php?symbol=BNBUSDT">BNBUSDT</a>
    </p>

    <?php if ($error_message): ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php elseif (empty($data_history)): ?>
        <p>Belum ada data di database untuk <?php echo htmlspecialchars($symbolToShow); ?>.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Waktu Pengambilan</th>
                    <th>Harga (USDT)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data_history as $row): ?>
                    <tr>
                        <td><?php echo $row['fetch_time']; ?></td>
                        <td><?php echo number_format($row['price'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>
</html>