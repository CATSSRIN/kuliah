<?php
// koneksi.php
$host = "localhost";
$user = "catssmyi_bmkg"; // Sesuaikan dengan user database Anda
$pass = "UpUkgt2tJW8SQnMLpSuZ";     // Sesuaikan dengan password database Anda
$db   = "catssmyi_bmkg";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>