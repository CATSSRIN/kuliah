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
$input = json_decode(file_get_contents('php://input'), true);
$items = $input['items'] ?? [];
if (!is_array($items) || count($items)===0)
jsonResponse(["success"=>false,"message"=>"No items"],400);
$total = 0;
foreach ($items as $it) {
$pid = intval($it['food_id']);
$qty = intval($it['qty']);
$stmt = $pdo->prepare("SELECT price FROM foods WHERE id = ?");
$stmt->execute([$pid]);
$p = $stmt->fetch();
if (!$p) jsonResponse(["success"=>false,"message"=>"Food not
found"],400);
$total += $p['price'] * $qty;
}
$pdo->beginTransaction();
$ins = $pdo->prepare("INSERT INTO orders (user_id, total) VALUES
(?, ?)");
$ins->execute([$user['id'], $total]);
$orderId = $pdo->lastInsertId();
$insItem = $pdo->prepare("INSERT INTO order_items (order_id,
food_id, qty, price) VALUES (?, ?, ?, ?)");
foreach ($items as $it) {
$pid = intval($it['food_id']);
$qty = intval($it['qty']);
$pstmt = $pdo->prepare("SELECT price FROM foods WHERE id = ?");
$pstmt->execute([$pid]);
$pp = $pstmt->fetch();
$insItem->execute([$orderId, $pid, $qty, $pp['price']]);
}
$pdo->commit();
jsonResponse(["success"=>true,"message"=>"Order
placed","order_id"=>$orderId]);
?>