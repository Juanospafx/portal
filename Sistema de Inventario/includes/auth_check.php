<?php
// includes/auth_check.php para Sistema de Inventario (v4 - Rutas de Servidor)

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    return;
}

if (!isset($_GET['jwt'])) {
    header('Location: https://portal.brightronix.net/login.php');
    exit;
}

// Definir una ruta base para evitar problemas con la inclusión de ficheros
// $_SERVER['DOCUMENT_ROOT'] apunta a /public_html/
$base_path = $_SERVER['DOCUMENT_ROOT'] . '/Sistema de Inventario';

require_once $base_path . '/libs/php-jwt/src/JWT.php';
require_once $base_path . '/libs/php-jwt/src/Key.php';
require_once $base_path . '/libs/php-jwt/src/ExpiredException.php';
require_once $base_path . '/libs/php-jwt/src/BeforeValidException.php';
require_once $base_path . '/libs/php-jwt/src/SignatureInvalidException.php';
require_once $base_path . '/includes/load.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$jwt = $_GET['jwt'];
$secret_key = '02b625d28e62a81fc693a13dd2c716a8c61b1ca61650db0d5b3b7ee7f3e34a3f';

try {
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    $user_data = (array) $decoded->data;

    $app_access_list = array_map('trim', explode(',', $user_data['app_access']));
    if (!in_array('Sistema de Inventario', $app_access_list)) {
        die('Acceso denegado. No tienes permiso para esta aplicación.');
    }

    $user = find_by_id('users', (int)$user_data['id']);
    if (!$user) {
        die('Error: El usuario del token no existe en la BD local.');
    }

    global $session;
    $session->login($user['id']);
    updateLastLogIn($user['id']);

    // Limpiar la URL y redirigir
    $redirect_url = '/Sistema de Inventario/home.php';
    header('Location: ' . $redirect_url);
    exit;

} catch (Exception $e) {
    http_response_code(401);
    die('Error de autenticación: ' . $e->getMessage());
}
?>