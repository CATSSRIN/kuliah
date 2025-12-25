<?php
require 'db.php';
require 'helpers.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
$input = json_decode(file_get_contents('php://input'), true);
$email = trim($input['email'] ?? '');
$password = $input['password'] ?? '';
if (!$email || !$password)
jsonResponse(["success"=>false,"message"=>"Email & password
wajib"],400);
$stmt = $pdo->prepare("SELECT id,name,email,password,role FROM
users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();
if ($user && password_verify($password, $user['password'])) {
$token = bin2hex(random_bytes(16));
$u = $pdo->prepare("UPDATE users SET token = ? WHERE id = ?");
$u->execute([$token, $user['id']]);
jsonResponse(["success"=>true,"data"=>["id"=>$user['id'],"name"=>$user['name'],"email"=>$user['email'],"role"=>$user['role'],"token"=>$token]]);
} else {
jsonResponse(["success"=>false,"message"=>"Email atau password
salah"],401);
}
?>