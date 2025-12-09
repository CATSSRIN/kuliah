<?php
// NYALAKAN ERROR REPORTING (Sangat Penting untuk Debugging)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'ClaheService.php';

$paths = null; // Variable penampung hasil
$errorMsg = null;
$successMsg = null;

// Default values
$defaultTile = 50;
$defaultClip = 3.0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tileVal = isset($_POST['tileSize']) ? (int)$_POST['tileSize'] : 50;
    $clipVal = isset($_POST['clipLimit']) ? (float)$_POST['clipLimit'] : 3.0;
    
    $defaultTile = $tileVal;
    $defaultClip = $clipVal;

    try {
        if (!isset($_FILES['imageFile'])) {
            throw new Exception("Tidak ada file yang diupload.");
        }

        $clahe = new ClaheService();
        
        // Panggil service dan tampung array hasilnya
        $paths = $clahe->processImage($_FILES['imageFile'], $tileVal, $tileVal, $clipVal);
        
        $successMsg = "Proses CLAHE berhasil!";

    } catch (Exception $e) {
        $errorMsg = "Terjadi Kesalahan: " . $e->getMessage();
    }
}
?>