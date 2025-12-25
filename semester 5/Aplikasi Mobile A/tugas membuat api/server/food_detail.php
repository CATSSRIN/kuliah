<?php
require 'db.php';
require 'helpers.php';
header("Access-Control-Allow-Origin: *");
$id = intval($_GET['id'] ?? 0);
if (!$id) jsonResponse(["success"=>false,"message"=>"Missing
id"],400);
$stmt = $pdo->prepare("SELECT f.id, f.name, f.description,
f.price, f.image, r.name AS restaurant FROM foods f LEFT JOIN
restaurants r ON f.restaurant_id = r.id WHERE f.id = ?");
$stmt->execute([$id]);
$food = $stmt->fetch();
if ($food) echo json_encode(["success"=>true,"data"=>$food]);
else jsonResponse(["success"=>false,"message"=>"Not found"],404);
?>