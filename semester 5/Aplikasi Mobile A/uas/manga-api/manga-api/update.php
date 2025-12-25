<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
  http_response_code(400);
  echo json_encode(["error" => "ID is required"]);
  exit;
}

$id    = intval($data['id']);
$title = $data['title'] ?? null;
$cover = $data['cover'] ?? null;
$pages = isset($data['pages']) ? json_encode($data['pages']) : null;

$stmt = $conn->prepare("UPDATE manga SET title=?, cover=?, pages=? WHERE id=?");
$stmt->bind_param("sssi", $title, $cover, $pages, $id);
$stmt->execute();

echo json_encode(["success" => true]);
?>
