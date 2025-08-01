<?php
// Sistema de Inventario/auth_check.php (v2 - Rutas de Hosting Dinámicas)

// 1. Construir la ruta absoluta al validador compartido de forma dinámica.
$common_validator_path = dirname(__DIR__, 2) . '/common/auth_jwt.php';

// 2. Incluir el validador.
require_once $common_validator_path;

// 3. Definir la ruta base de ESTA aplicación.
$app_base_path = __DIR__;

// 4. Ejecutar la validación.
validate_jwt_and_start_session('Sistema de Inventario', $app_base_path);

// 5. Cargar el resto de las dependencias de la aplicación.
require_once $app_base_path . '/includes/load.php';

// 6. Verificar si el usuario está logueado según el sistema de sesión local.
if (!$session->isUserLoggedIn(true)) {
    $session->msg("d", "Error de sesión. Por favor, inicie sesión de nuevo.");
    header('Location: https://portal.brightronix.net/login.php?error=session_mismatch');
    exit;
}