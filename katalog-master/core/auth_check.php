<?php
// katalog-master/core/auth_check.php (v8 - Rutas de Hosting Dinámicas)

// 1. Construir la ruta absoluta al validador compartido de forma dinámica.
// Sube tres niveles desde /core para llegar a /domains/ y luego entra en /common/.
$common_validator_path = dirname(__DIR__, 3) . '/common/auth_jwt.php';

// 2. Incluir el validador.
require_once $common_validator_path;

// 3. Definir la ruta base de ESTA aplicación para que el helper encuentre 'vendor/autoload.php'.
$app_base_path = dirname(__DIR__); // Apunta a la raíz de katalog-master

// 4. Ejecutar la validación.
validate_jwt_and_start_session('katalog-master', $app_base_path);

// Opcional: Verificación adicional en la BD local (si es necesaria).
// ...