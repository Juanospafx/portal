<?php
/**
 * Validador JWT Centralizado para Brightronix SSO.
 *
 * Este script maneja la autenticación de usuarios en las aplicaciones hijas
 * a través de un token JWT proporcionado en la URL.
 *
 * @version 2.0
 * @date 2025-08-01
 */

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

/**
 * Valida un token JWT, establece la sesión del usuario y limpia la URL.
 *
 * @param string $required_app_name El identificador de la app que requiere acceso (ej. 'clockin_app').
 * @param string $app_base_path La ruta absoluta al directorio raíz de la aplicación hija.
 * @param string $portal_login_url La URL a la que se redirigirá en caso de fallo.
 */
function validate_jwt_and_start_session(string $required_app_name, string $app_base_path, string $portal_login_url = 'https://portal.brightronix.net/login.php') {

    // 1. Iniciar la sesión si no está activa.
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // 2. Si el usuario ya tiene una sesión válida en esta app, no hacer nada.
    if (isset($_SESSION['user_id'])) {
        return;
    }

    // 3. Si no hay sesión y no hay token JWT, redirigir al portal.
    if (!isset($_GET['jwt'])) {
        header('Location: ' . $portal_login_url . '?error=missing_token');
        exit;
    }

    // 4. Cargar el autoloader de Composer de la aplicación hija.
    $autoloader = $app_base_path . '/vendor/autoload.php';
    if (!file_exists($autoloader)) {
        http_response_code(500);
        die("Error Crítico: No se encuentra 'vendor/autoload.php'. Ejecuta 'composer install' en el directorio de la aplicación.");
    }
    require_once $autoloader;

    // 5. Definir la clave secreta (único lugar donde se define para las apps hijas).
    $secret_key = '02b625d28e62a81fc693a13dd2c716a8c61b1ca61650db0d5b3b7ee7f3e34a3f';
    $jwt = $_GET['jwt'];

    try {
        // 6. Decodificar el token. La librería verifica la firma y la expiración.
        $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
        $user_data = (array) $decoded->data;

        // 7. Verificar que el usuario tiene permiso para acceder a esta aplicación.
        $app_access_list = array_map('trim', explode(',', $user_data['app_access']));
        if (!in_array($required_app_name, $app_access_list)) {
            header('Location: ' . $portal_login_url . '?error=access_denied&app=' . $required_app_name);
            exit;
        }

        // 8. Crear las variables de sesión para la aplicación hija.
        $_SESSION['user_id'] = $user_data['id'];
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['email'] = $user_data['email'];
        // Puedes añadir más datos del token a la sesión si es necesario.

        // 9. Redirigir a la misma URL sin el token para limpiar la barra de direcciones.
        $redirect_url = strtok($_SERVER["REQUEST_URI"], '?');
        header('Location: ' . $redirect_url);
        exit;

    } catch (ExpiredException $e) {
        header('Location: ' . $portal_login_url . '?error=expired_token');
        exit;
    } catch (SignatureInvalidException $e) {
        header('Location: ' . $portal_login_url . '?error=invalid_signature');
        exit;
    } catch (Exception $e) {
        // Captura cualquier otro error de decodificación.
        header('Location: ' . $portal_login_url . '?error=token_error&details=' . urlencode($e->getMessage()));
        exit;
    }
}
