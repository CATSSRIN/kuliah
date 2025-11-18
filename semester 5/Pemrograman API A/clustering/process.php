<?php
require 'cluster.php';

$file = $_GET['file'];
$filePath = "data/" . $file;

$results = runClustering($filePath);

// Simpan hasil ke session untuk ditampilkan nanti
session_start();
$_SESSION['clustering_result'] = $results;

header("Location: results.php");
exit;
?>
