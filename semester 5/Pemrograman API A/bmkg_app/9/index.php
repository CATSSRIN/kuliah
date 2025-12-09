<?php
include 'koneksi.php';

// Data Cuaca Saat Ini
$sql_now = "SELECT * FROM prakiraan_cuaca WHERE waktu >= NOW() ORDER BY waktu ASC LIMIT 1";
$result_now = $conn->query($sql_now);
$current = $result_now->fetch_assoc();

// Data Prakiraan
$sql_list = "SELECT * FROM prakiraan_cuaca WHERE waktu > NOW() ORDER BY waktu ASC";
$result_list = $conn->query($sql_list);

function getIcon($cuaca) {
    $cuaca = strtolower($cuaca);
    if (strpos($cuaca, 'petir') !== false) return '⛈️';
    if (strpos($cuaca, 'hujan') !== false) return '🌧️';
    if (strpos($cuaca, 'cerah berawan') !== false) return '⛅';
    if (strpos($cuaca, 'cerah') !== false) return '☀️';
    if (strpos($cuaca, 'berawan') !== false) return '☁️';
    if (strpos($cuaca, 'kabut') !== false) return '🌫️';
    return '🌡️';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuaca Surabaya</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root { --primary: #007bff; --bg: #f4f7f6; --card-bg: #ffffff; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg); margin: 0; padding: 20px; color: #333; }
        .container { max-width: 800px; margin: 0 auto; }
        
        /* Weather Card */
        .weather-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; padding: 30px; border-radius: 20px; text-align: center;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1); margin-bottom: 30px;
        }
        .weather-card h1 { margin: 0; font-size: 2.5rem; }
        .weather-card .temp { font-size: 4rem; font-weight: 600; margin: 10px 0; }
        .weather-card .desc { font-size: 1.5rem; text-transform: capitalize; }
        
        /* Button Style */
        .btn-update {
            display: block; width: 100%; border: none; cursor: pointer;
            background: white; color: #764ba2; padding: 15px;
            border-radius: 12px; font-weight: bold; font-size: 1rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: transform 0.2s;
            margin-bottom: 30px;
        }
        .btn-update:hover { background-color: #f8f9fa; }
        .btn-update:active { transform: scale(0.98); }
        .btn-update:disabled { opacity: 0.7; cursor: not-allowed; }

        /* Table */
        .table-container { background: var(--card-bg); border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px 20px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; color: #666; }
        
        @media (max-width: 600px) {
            .weather-card .temp { font-size: 3rem; }
            th, td { padding: 12px 10px; font-size: 0.9rem; }
        }
    </style>
</head>
<body>

<div class="container">
    
    <button id="btnUpdate" class="btn-update">🔄 Update Data Terbaru</button>

    <?php if ($current): ?>
    <div class="weather-card">
        <div class="location">📍 <?= htmlspecialchars($current['desa']) ?>, Surabaya</div>
        <div style="font-size: 5rem; margin: 10px 0;"><?= getIcon($current['cuaca']) ?></div>
        <div class="temp"><?= $current['suhu'] ?>°C</div>
        <div class="desc"><?= $current['cuaca'] ?></div>
        <div style="font-size: 0.9rem; margin-top: 10px;">
            <?= date('d M Y, H:i', strtotime($current['waktu'])) ?> WIB
        </div>
    </div>
    <?php else: ?>
        <div class="weather-card"><h3>Data kosong. Klik update.</h3></div>
    <?php endif; ?>

    <h3 style="color: #555; margin-left: 10px;">Prakiraan Mendatang</h3>
    <div class="table-container">
        <table>
            <thead><tr><th>Waktu</th><th>Cuaca</th><th>Suhu</th></tr></thead>
            <tbody>
                <?php if ($result_list->num_rows > 0): ?>
                    <?php while($row = $result_list->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <strong><?= date('H:i', strtotime($row['waktu'])) ?></strong><br>
                                <span style="font-size: 0.8em; color: #888;"><?= date('d M', strtotime($row['waktu'])) ?></span>
                            </td>
                            <td>
                                <span style="font-size: 1.2em;"><?= getIcon($row['cuaca']) ?></span> 
                                <?= htmlspecialchars($row['cuaca']) ?>
                            </td>
                            <td><b><?= $row['suhu'] ?>°C</b></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="3" align="center">Belum ada data.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div style="text-align: center; margin-top: 30px; color: #aaa; font-size: 0.8rem;">
        &copy; <?= date('Y') ?> - Data source: BMKG
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('btnUpdate').addEventListener('click', function() {
        const btn = this;
        // 1. Ubah tombol jadi loading
        btn.innerHTML = '⏳ Sedang mengambil data...';
        btn.disabled = true;

        // 2. Panggil api.php di latar belakang
        fetch('api.php')
            .then(response => response.json()) // Baca respon sebagai JSON
            .then(data => {
                // 3. Tampilkan Notifikasi Sesuai Status
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#764ba2',
                        confirmButtonText: 'Oke'
                    }).then((result) => {
                        // 4. Reload halaman setelah user klik Oke agar data tabel berubah
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'Coba Lagi'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan koneksi.',
                    icon: 'error'
                });
            })
            .finally(() => {
                // Kembalikan tombol seperti semula (jika tidak reload)
                btn.innerHTML = '🔄 Update Data Terbaru';
                btn.disabled = false;
            });
    });
</script>

</body>
</html>