<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
  http_response_code(400);
  echo json_encode(["error" => "ID is required"]);
  exit;
}

$id = intval($data['id']);

$stmt = $conn->prepare("DELETE FROM manga WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

echo json_encode(["success" => true]);
?>
