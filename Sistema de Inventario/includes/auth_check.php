<?php
// includes/auth_check.php para Sistema de Inventario (v3 - Final)

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

// Cargar dependencias de la aplicación y de JWT
require_once __DIR__ . '/../libs/php-jwt/src/Exception.php';
require_once __DIR__ . '/../libs/php-jwt/src/JWT.php';
require_once __DIR__ . '/../libs/php-jwt/src/Key.php';
require_once __DIR__ . '/load.php'; // Carga las funciones de la app como find_by_id y el objeto $session

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$jwt = $_GET['jwt'];
$secret_key = 'tu_clave_secreta_muy_larga_y_aleatoria_aqui_cambiala_en_produccion_por_favor_mas_de_32_caracteres';

try {
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    $user_data = (array) $decoded->data;

    $app_access_list = array_map('trim', explode(',', $user_data['app_access']));
    if (!in_array('Sistema de Inventario', $app_access_list)) {
        die('Acceso denegado. No tienes permiso para usar esta aplicación.');
    }

    // Es crucial que el usuario exista en la BD de esta app
    $user = find_by_id('users', (int)$user_data['id']);
    if (!$user) {
        die('Error de autenticación: El usuario del token no existe en la base de datos de esta aplicación.');
    }

    // Usar el sistema de sesión de la propia aplicación
    global $session;
    $session->login($user['id']);
    updateLastLogIn($user['id']);

    $redirect_url = strtok($_SERVER['REQUEST_URI'], '?');
    header('Location: ' . $redirect_url);
    exit;

} catch (Exception $e) {
    http_response_code(401);
    die('Acceso denegado. El token de autenticación no es válido o ha expirado. (Error: ' . $e->getMessage() . ')');
}
?>