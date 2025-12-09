<?php
// index.php
include 'koneksi.php';

// Fungsi helper untuk menerjemahkan Kode Cuaca BMKG
function artikanKodeCuaca($kode) {
    // Referensi kode: https://data.bmkg.go.id/prakiraan-cuaca/
    $kode_bmkg = [
        0 => "Cerah",
        1 => "Cerah Berawan",
        2 => "Cerah Berawan",
        3 => "Berawan",
        4 => "Berawan Tebal",
        5 => "Udara Kabur",
        10 => "Asap",
        45 => "Kabut",
        60 => "Hujan Ringan",
        61 => "Hujan Sedang",
        63 => "Hujan Lebat",
        80 => "Hujan Petir",
        95 => "Hujan Petir",
        97 => "Hujan Petir"
    ];

    return isset($kode_bmkg[$kode]) ? $kode_bmkg[$kode] : "Kode tidak dikenal ($kode)";
}

// Ambil data dari database, urutkan berdasarkan Kota dan Waktu
$sql = "SELECT * FROM prakiraan_cuaca ORDER BY nama_kota ASC, waktu ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Cuaca BMKG</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .update-btn { 
            padding: 10px 15px; background: #007bff; color: white; 
            text-decoration: none; border-radius: 5px; 
        }
        .update-btn:hover { background: #0056b3; }
    </style>
</head>
<body>

    <h2>Prakiraan Cuaca (Sumber: BMKG)</h2>
    
    <p>
        <a href="api.php" class="update-btn">Update Data dari BMKG</a>
    </p>

    <table>
        <thead>
            <tr>
                <th>Kota / Area</th>
                <th>Waktu Prakiraan</th>
                <th>Cuaca</th>
                <th>Suhu (°C)</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nama_kota']) ?></td>
                        <td><?= date('d M Y, H:i', strtotime($row['waktu'])) ?></td>
                        <td>
                            <strong><?= artikanKodeCuaca($row['kode_cuaca']) ?></strong>
                        </td>
                        <td><?= $row['suhu'] ?>°C</td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center">Belum ada data. Silakan klik tombol Update.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>