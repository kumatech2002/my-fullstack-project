<?php
// api/login.php
require_once 'helpers.php';
$creds = json_decode(file_get_contents('php://input'), true);

$username = $creds['username'] ?? '';
$password = $creds['password'] ?? '';

// Replace with secure user storage / hashed password check
if ($username === 'admin' && $password === '12345') {
  $payload = [
    'sub' => $username,
    'role' => 'admin',
    'iat' => time(),
    'exp' => time() + 3600*4
  ];
  $jwt = JWT::encode($payload, JWT_SECRET, 'HS256');
  jsonResponse(['token'=>$jwt]);
}
jsonResponse(['error'=>'invalid credentials'], 401);
