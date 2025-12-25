<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['title']) || !isset($data['content'])) {
  http_response_code(400);
  echo json_encode(["error" => "Title and content are required"]);
  exit;
}

$title = $data['title'];
$cover = $data['cover'] ?? null;
$content = $data['content'];

$stmt = $conn->prepare("INSERT INTO lightnovel (title, cover, content) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $title, $cover, $content);

if ($stmt->execute()) {
  echo json_encode(["success" => true, "id" => $stmt->insert_id]);
} else {
  http_response_code(500);
  echo json_encode(["error" => "Failed to insert light novel"]);
}
?>
