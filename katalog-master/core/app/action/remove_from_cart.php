<?php
session_start(); // Asegurar que la sesión está iniciada

// ✅ Verificar si el ID del producto fue enviado
if (!isset($_GET["id"])) {
    die("Error: No se proporcionó un ID de producto.");
}

$product_id = intval($_GET["id"]); // Convertir a número entero para mayor seguridad

// ✅ Verificar que el carrito existe en la sesión
if (!isset($_SESSION["cart"]) || empty($_SESSION["cart"])) {
    echo "<script>alert('El carrito está vacío.'); window.location='../../index.php?view=cart';</script>";
    exit;
}

// ✅ Si el producto existe en el carrito, eliminarlo
if (isset($_SESSION["cart"][$product_id])) {
    unset($_SESSION["cart"][$product_id]); // Eliminar producto del carrito
}

// ✅ Si el carrito queda vacío, limpiar la sesión del carrito
if (empty($_SESSION["cart"])) {
    unset($_SESSION["cart"]);
}

// ✅ Redirigir de vuelta al carrito de compras
header("Location: ../../../index.php?view=cart");
exit;
