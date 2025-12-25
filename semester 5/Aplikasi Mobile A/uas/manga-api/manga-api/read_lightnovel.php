<?php
include 'db.php';

$result = $conn->query("SELECT * FROM lightnovel ORDER BY created_at DESC");
$novels = [];

while ($row = $result->fetch_assoc()) {
  $row['id'] = intval($row['id']);
  $novels[] = $row;
}

echo json_encode($novels);
?>
