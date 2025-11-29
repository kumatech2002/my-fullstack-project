<?php
// api/admin_products.php
require_once 'helpers.php';
$user = validate_jwt();
if (!$user || ($user['role'] ?? '') !== 'admin') {
  jsonResponse(['error'=>'unauthorized'], 401);
}

// Now allow CRUD operations (GET list, POST add, PUT edit, DELETE)
$method = $_SERVER['REQUEST_METHOD'];
$productsPath = __DIR__ . '/../products.json';
$products = json_decode(file_get_contents($productsPath), true);

if ($method === 'GET') {
  jsonResponse(['products'=>array_values($products)]);
}

if ($method === 'POST') {
  $body = json_decode(file_get_contents('php://input'), true);
  // validate fields: name, price, image (url), category...
  // create new id
  $id = (count($products) ? max(array_keys($products)) + 1 : 1);
  $products[$id] = array_merge(['id'=>$id], $body);
  file_put_contents($productsPath, json_encode($products, JSON_PRETTY_PRINT));
  jsonResponse(['ok'=>true, 'id'=>$id], 201);
}

// Implement PUT / DELETE similarly (check input & sanitize)
