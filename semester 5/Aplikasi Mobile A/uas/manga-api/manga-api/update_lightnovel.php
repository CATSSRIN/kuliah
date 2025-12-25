<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
  http_response_code(400);
  echo json_encode(["error" => "ID is required"]);
  exit;
}

$id      = intval($data['id']);
$title   = $data['title'] ?? null;
$cover   = $data['cover'] ?? null;
$content = $data['content'] ?? null;

$stmt = $conn->prepare("UPDATE lightnovel SET title=?, cover=?, content=? WHERE id=?");
$stmt->bind_param("sssi", $title, $cover, $content, $id);

if ($stmt->execute()) {
  echo json_encode(["success" => true]);
} else {
  http_response_code(500);
  echo json_encode(["error" => "Failed to update light novel"]);
}
?>
