<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['username']) || !isset($data['password'])) {
  http_response_code(400);
  echo json_encode(["success" => false, "message" => "Username and password required"]);
  exit;
}

$username = trim($data['username']);
$password = trim($data['password']);

// Cek apakah username sudah ada
$check = $conn->prepare("SELECT id FROM users WHERE username = ?");
$check->bind_param("s", $username);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
  http_response_code(400);
  echo json_encode(["success" => false, "message" => "Username already exists"]);
  exit;
}

// Hash password
$hashed = password_hash($password, PASSWORD_DEFAULT);

// Insert user baru
$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hashed);

if ($stmt->execute()) {
  echo json_encode(["success" => true, "message" => "User registered"]);
} else {
  http_response_code(500);
  echo json_encode(["success" => false, "message" => "Failed to register: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
