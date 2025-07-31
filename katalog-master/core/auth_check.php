<?php
// core/auth_check.php para katalog-master (v4 - Final)

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    return; // El usuario ya tiene una sesión, no hacer nada.
}

if (!isset($_GET['jwt'])) {
    // Si no hay sesión ni token, redirigir al portal para que inicie sesión.
    header('Location: https://portal.brightronix.net/login.php');
    exit;
}

// --- Carga de dependencias --- //
// Cargar la librería JWT. La ruta es relativa a este fichero (core/auth_check.php).
require_once __DIR__ . '/libs/php-jwt/src/Exception.php';
require_once __DIR__ . '/libs/php-jwt/src/JWT.php';
require_once __DIR__ . '/libs/php-jwt/src/Key.php';
// Cargar el autoloader de la aplicación para poder usar sus funciones de base de datos.
require_once __DIR__ . '/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// --- Validación del Token --- //
$jwt = $_GET['jwt'];
// IMPORTANTE: Esta clave debe ser la misma que está en portal/includes/jwt_config.php
$secret_key = 'tu_clave_secreta_muy_larga_y_aleatoria_aqui_cambiala_en_produccion_por_favor_mas_de_32_caracteres';

try {
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    $user_data = (array) $decoded->data;

    // --- Verificación de Permisos y Usuario --- //
    $app_access_list = array_map('trim', explode(',', $user_data['app_access']));
    if (!in_array('katalog-master', $app_access_list)) {
        die('Acceso denegado. No tienes permiso para usar esta aplicación.');
    }

    $user = UserData::getById($user_data['id']);
    if(!$user){
        die('Error de autenticación: El usuario del token no existe en la base de datos de esta aplicación.');
    }

    // --- Creación de Sesión y Redirección --- //
    $_SESSION['user_id'] = $user->id;

    // Redirigir a la misma página para limpiar el token de la URL.
    $redirect_url = strtok($_SERVER['REQUEST_URI'], '?');
    header('Location: ' . $redirect_url);
    exit;

} catch (Exception $e) {
    // Si la decodificación falla (token expirado, firma inválida, etc.)
    http_response_code(401);
    die('Acceso denegado. El token de autenticación no es válido o ha expirado. Por favor, inicie sesión de nuevo. (Error: ' . $e->getMessage() . ')');
}
?>