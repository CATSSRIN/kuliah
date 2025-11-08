<?php
// Configuration and PDO connection establishment for MySQL
$host = 'localhost';
$dbname = 'vindax_data';
$user = 'your_username';
$password = 'your_password';
$pdo = null;

try {
    // Establish a PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection failure with an error message
    die("Database connection failed: " . $e->getMessage());
}
?>