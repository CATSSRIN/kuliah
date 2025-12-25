<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['title'])) {
  http_response_code(400);
  echo json_encode(["error" => "Title is required"]);
  exit;
}

$title = $data['title'];
$cover = $data['cover'] ?? null;
$pages = isset($data['pages']) ? json_encode($data['pages']) : json_encode([]);

$stmt = $conn->prepare("INSERT INTO manga (title, cover, pages) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $title, $cover, $pages);
$stmt->execute();

echo json_encode(["success" => true, "id" => $stmt->insert_id]);
?>
