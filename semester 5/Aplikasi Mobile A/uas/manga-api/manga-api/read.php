<?php
include 'db.php';

$result = $conn->query("SELECT * FROM manga ORDER BY created_at DESC");
$manga = [];

while ($row = $result->fetch_assoc()) {
  $row['id'] = intval($row['id']);
  $row['pages'] = $row['pages'] ? json_decode($row['pages'], true) : [];
  $manga[] = $row;
}

echo json_encode($manga);
?>
