<?php
session_start();
require_once "core/autoload.php"; 

// Obtener datos del formulario
$username = $_POST["username"];
$password = sha1(md5($_POST["password"])); // Encriptación compatible con la BD

// Conectar a la base de datos
$base = new Database();
$con = $base->connect();

// Buscar usuario por email o username
$sql = "SELECT * FROM user WHERE (email = '$username' OR username = '$username') AND password = '$password' AND is_active = 1";
$query = $con->query($sql);

$user = $query->fetch_assoc();

if ($user) {
    // Iniciar sesión con el ID del usuario
    Session::setUID($user["id"]);

    // Obtener roles del usuario
    $roles = UserData::getRolesByUserId($user["id"]);
    if (empty($roles)) {
        echo "<script>alert('No tienes permisos para acceder.');</script>";
        Session::unsetUID();
        echo "<script>window.location='login.php';</script>";
        exit;
    }

    Session::setRoles($roles);

    // Redirigir según el rol
    if (Session::hasRole("Administrador")) {
        header("Location: admin/layout.php");
    } else {
        header("Location: /katalog-master/katalog-master/"); // Cambia esto según la ruta del usuario
    }
    exit;
} else {
    // Si los datos son incorrectos
    echo "<script>alert('Usuario o contraseña incorrectos.');</script>";
    echo "<script>window.location='login.php';</script>";
    exit;
}
?>
