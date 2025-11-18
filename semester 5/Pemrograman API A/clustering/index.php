<?php
// --- LOGIKA PENGECEKAN FILE ---

// 1. Tentukan file/folder yang akan kita gunakan
//    (Harus konsisten dengan file process.php Anda)
$uploadFolder = "data"; 
$defaultFile = "dataset.csv";
$defaultFilePath = $uploadFolder . "/" . $defaultFile; // Path lengkap: "data/dataset.csv"

// 2. Cek apakah file "data/dataset.csv" ada
if (file_exists($defaultFilePath)) {
    
    // 3. Jika ADA:
    // Langsung redirect ke process.php dan kirim nama filenya
    header("Location: process.php?file=" . $defaultFile);
    exit; // Hentikan eksekusi script agar HTML di bawah tidak tampil
}

// 4. Jika TIDAK ADA:
// Script akan lanjut ke bawah dan merender HTML secara normal.

?>
<!DOCTYPE html>
<html>
<head>
    <title>Clustering K-Means — Upload CSV</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container">
    <h2>K-Means Clustering</h2>
    
    <p>File 'data/dataset.csv' tidak ditemukan. Silakan upload dataset baru.</p>

    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <label>Upload Dataset (.csv)</label>
        <input type="file" name="dataset" required accept=".csv">
        <button type="submit">Proses</button>
    </form>
</div>

</body>
</html>