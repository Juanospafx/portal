<?php
require_once "core/autoload.php";

// ✅ Verificar si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 🚨 Depurar: Verificar si los roles están llegando correctamente
    if (!isset($_POST["roles"]) || empty($_POST["roles"])) {
        die("<script>alert('Error: Debes seleccionar al menos un rol.'); window.location='index.php?view=newuser';</script>");
    }

    // ✅ Recibir datos del formulario
    $name = trim($_POST["name"]);
    $lastname = trim($_POST["lastname"]);
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = sha1(md5($_POST["password"])); // 🔒 Encriptar la contraseña
    $roles = $_POST["roles"]; // 📌 Guardar roles seleccionados

    // ✅ Crear usuario en la base de datos
    $user = new UserData();
    $user->name = $name;
    $user->lastname = $lastname;
    $user->username = $username;
    $user->email = $email;
    $user->password = $password;
    $user_id = $user->add(); // 📌 Insertar usuario y obtener el ID generado

    // ✅ Asignar roles al usuario en la tabla `user_roles`
    foreach ($roles as $role_id) {
        UserData::assignRole($user_id, $role_id);
    }

    // ✅ Redirigir de vuelta a la lista de usuarios con un mensaje de éxito
    echo "<script>alert('Usuario agregado correctamente.'); window.location='index.php?view=users';</script>";
    exit;
}
?>
