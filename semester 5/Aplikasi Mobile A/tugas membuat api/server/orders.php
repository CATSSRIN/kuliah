<?php
require 'db.php';
require 'helpers.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Content-
Type");
$headers = getallheaders();
$auth = $headers['Authorization'] ?? ($headers['authorization'] ??
'');
if (!$auth || !preg_match('/Bearer\s(\S+)/',$auth,$m))
jsonResponse(["success"=>false,"message"=>"Token required"],401);
$token = $m[1];
$u = $pdo->prepare("SELECT id FROM users WHERE token = ?");
$u->execute([$token]);
$user = $u->fetch();
if (!$user) jsonResponse(["success"=>false,"message"=>"Invalid
token"],401);
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ?
ORDER BY created_at DESC");
$stmt->execute([$user['id']]);
$orders = $stmt->fetchAll();
foreach ($orders as &$o) {
$stmt2 = $pdo->prepare("SELECT oi.*, f.name AS food_name FROM
order_items oi LEFT JOIN foods f ON oi.food_id = f.id WHERE
oi.order_id = ?");
$stmt2->execute([$o['id']]);
$o['items'] = $stmt2->fetchAll();
}
jsonResponse(["success"=>true,"data"=>$orders]);
?>