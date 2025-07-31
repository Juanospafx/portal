<?php
// portal/backend/validate_token.php
require_once '../includes/jwt_config.php';
require_once '../libs/php-jwt/src/JWT.php';
require_once '../libs/php-jwt/src/Key.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

$response = ['valid' => false, 'message' => 'Invalid token'];

if (isset($_GET['jwt'])) {
    $jwt = $_GET['jwt'];
    try {
        $decoded = JWT::decode($jwt, new Key(JWT_SECRET_KEY, 'HS256'));
        $response = ['valid' => true, 'user_data' => (array) $decoded->data];
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }
}

echo json_encode($response);
?>