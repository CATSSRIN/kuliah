<?php
$folder = "uploads/";

if (!file_exists($folder)) {
    mkdir($folder, 0777, true);
}

if (isset($_FILES['dataset'])) {
    $fileName = time() . "_" . $_FILES['dataset']['name'];
    $filePath = $folder . $fileName;

    move_uploaded_file($_FILES['dataset']['tmp_name'], $filePath);

    header("Location: process.php?file=$fileName");
    exit;
}
?>
