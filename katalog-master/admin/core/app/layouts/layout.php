<?php
// Iniciar sesión solo si no está activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cargar archivos esenciales


// **Si no hay sesión activa, redirigir a login.php**
