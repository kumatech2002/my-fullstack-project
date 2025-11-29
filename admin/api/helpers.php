<?php
// api/helpers.php
require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

const JWT_SECRET = 'replace_with_a_long_random_secret_string';

function jsonResponse($data, $code=200){
  http_response_code($code);
  header('Content-Type: application/json');
  echo json_encode($data);
  exit;
}

function getBearerToken(){
  $h = getallheaders();
  if (!empty($h['Authorization'])) {
    if (preg_match('/Bearer\s(\S+)/', $h['Authorization'], $matches)) return $matches[1];
  }
  return null;
}

function validate_jwt(){
  $token = getBearerToken();
  if (!$token) return null;
  try {
    $payload = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
    return (array)$payload;
  } catch(Exception $e){
    return null;
  }
}
