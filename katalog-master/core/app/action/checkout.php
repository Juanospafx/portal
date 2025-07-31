<?php
session_start();
ob_start(); // ✅ Evitar salida previa de datos

// ✅ Verificar que el usuario esté autenticado antes de procesar la compra
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// ✅ Vaciar el carrito
unset($_SESSION["cart"]);

// ✅ Redirigir al carrito
header("Location: ../../../index.php?view=cart");
exit;
