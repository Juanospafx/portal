<?php
require_once "core/autoload.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 🔹 Validar si el usuario existe antes de actualizar
    $user = UserData::getById($_POST["user_id"]);
    if (!$user) {
        die("<script>alert('Error: Usuario no encontrado.'); window.location='index.php?view=users';</script>");
    }

    // 🔹 Recibir datos del formulario
    $user->name = htmlspecialchars($_POST["name"]);
    $user->lastname = htmlspecialchars($_POST["lastname"]);
    $user->username = htmlspecialchars($_POST["username"]);
    $user->email = htmlspecialchars($_POST["email"]);
    $user->is_active = isset($_POST["is_active"]) ? 1 : 0;

    // 🔹 Actualizar usuario en la base de datos
    $user->update();

    // ✅ Si se proporciona una nueva contraseña, actualizarla
    if (!empty($_POST["password"])) {
        $user->password = sha1(md5($_POST["password"]));
        $user->update_passwd();
        echo "<script>alert('Contraseña actualizada correctamente.');</script>";
    }

    // ✅ Actualizar roles del usuario en `user_roles`
    if (isset($_POST["roles"])) {
        // 🔹 Eliminar roles anteriores
        UserData::removeRoles($user->id);

        // 🔹 Asignar los nuevos roles seleccionados
        foreach ($_POST["roles"] as $role_id) {
            UserData::assignRole($user->id, $role_id);
        }
    }

    // ✅ Redireccionar después de actualizar
    echo "<script>alert('Usuario actualizado correctamente.'); window.location='index.php?view=users';</script>";
}
?>
