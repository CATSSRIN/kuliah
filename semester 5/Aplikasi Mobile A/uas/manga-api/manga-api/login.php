<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['username']) || !isset($data['password'])) {
  http_response_code(400);
  echo json_encode(["success" => false, "message" => "Username dan password wajib diisi"]);
  exit;
}

$username = trim($data['username']);
$password = trim($data['password']);

// Ambil data user dari database
$stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
  // Verifikasi password
  if (password_verify($password, $row['password'])) {
    echo json_encode([
      "success" => true,
      "message" => "Login berhasil",
      "user_id" => $row['id'],
      "username" => $row['username']
    ]);
  } else {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Password salah"]);
  }
} else {
  http_response_code(404);
  echo json_encode(["success" => false, "message" => "User tidak ditemukan"]);
}

$stmt->close();
$conn->close();
?>
