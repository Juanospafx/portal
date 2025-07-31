<?php
session_start();
require_once "core/autoload.php"; 

// Si el usuario ya está autenticado, redirigirlo al dashboard
if (Session::issetUID()) {
    header("Location: admin/layout.php");
    exit;
}

// Manejo de errores
$error = null;

// Si el formulario de login fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["email"]; // Asegurar que coincida con el formulario
    $password = $_POST["password"];

    // Buscar usuario en la base de datos
    $user = UserData::getByUsername($username);

    if ($user && sha1(md5($password)) == $user->password) {
        // Iniciar sesión y guardar datos
        Session::setUID($user->id);
        $roles = UserData::getRolesByUserId($user->id);

        if (empty($roles)) {
            $error = "No tienes permisos para acceder.";
            Session::unsetUID();
        } else {
            Session::setRoles($roles);
            
            // Redirigir según el rol
            if (Session::hasRole("Administrador")) {
                header("Location: admin/layout.php");
            } else {
                header("Location: /katalog-master/katalog-master/"); // Cambia esto según la ruta del usuario
            }
            exit;
        }
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}

// Si hubo un error, redirigir de nuevo al login con mensaje de error
header("Location: login.php?error=" . urlencode($error));
exit;
