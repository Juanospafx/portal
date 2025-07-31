<?php
session_start();
require_once "core/autoload.php";

// Verificar si el usuario es administrador
if (!isset($_SESSION["admin_id"])) {
    Core::redir("./");
    exit;
}

// Verificar que haya datos en la petición
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST) && is_array($_POST)) {
    foreach ($_POST as $p => $k) {
        $p = trim($p);
        $k = trim($k);

        // Asegurar que la clave y el valor no estén vacíos
        if (!empty($p) && !empty($k) && is_string($p) && is_string($k)) {
            ConfigurationData::updateValFromName($p, $k);
        }
    }

    // Redirigir a la configuración de pagos
    Core::redir("./?view=payment_settings");
    exit;
}

// Si no se cumplen las condiciones, redirigir al inicio
Core::redir("./");
exit;
