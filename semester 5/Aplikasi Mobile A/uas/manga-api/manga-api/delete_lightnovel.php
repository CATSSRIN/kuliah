<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
  http_response_code(400);
  echo json_encode(["error" => "ID is required"]);
  exit;
}

$id = intval($data['id']);

$stmt = $conn->prepare("DELETE FROM lightnovel WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
  echo json_encode(["success" => true]);
} else {
  http_response_code(500);
  echo json_encode(["error" => "Failed to delete light novel"]);
}
?>
