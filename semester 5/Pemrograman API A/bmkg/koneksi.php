<?php
// koneksi.php
$host = "localhost";
$user = "root"; // Sesuaikan dengan user database Anda
$pass = "";     // Sesuaikan dengan password database Anda
$db   = "bmkg_cuaca";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>