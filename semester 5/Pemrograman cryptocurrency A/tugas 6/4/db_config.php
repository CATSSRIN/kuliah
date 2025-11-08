<?php
// db_config.php

define('DB_HOST', 'localhost'); // Ganti dengan host Anda (biasanya 'localhost')
define('DB_USER', 'catssmyi_myuser_vindax_data');      // Ganti dengan username database Anda
define('DB_PASS', 'CBNKMNRVLBbJBBSGS7Cv');          // Ganti dengan password database Anda
define('DB_NAME', 'catssmyi_myuser_vindax_data'); // Ganti dengan nama database yang Anda buat

try {
    // Membuat koneksi PDO
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    
    // Mengatur mode error PDO ke exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Mengatur fetch mode default
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Die (berhenti) dan tampilkan pesan error jika koneksi gagal
    die("ERROR: Tidak dapat terhubung ke database. " . $e->getMessage());
}
?>