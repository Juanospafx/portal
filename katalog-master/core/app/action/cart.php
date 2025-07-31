<?php
session_start();
require_once "core/autoload.php"; // Ajusta según tu estructura

// Verificar si se envió un array de productos o un único product_id
if (isset($_GET['selected_items']) && is_array($_GET['selected_items'])) {
    $product_ids = $_GET['selected_items'];
} elseif (isset($_GET['product_id'])) {
    $product_ids = [ $_GET['product_id'] ];
} else {
    // Si no se envía ninguno, redirigir a la página principal
    header("Location: index.php");
    exit;
}

$quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1;
$unit     = isset($_GET['unit']) ? $_GET['unit'] : 'ea'; // Valor por defecto "ue"

// Crear la sesión del carrito si no existe
if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

// Iterar sobre los IDs y agregarlos al carrito
foreach ($product_ids as $product_id) {
    // Asegurarse de que product_id sea un número entero
    $product_id = intval($product_id);

    if (!isset($_SESSION["cart"][$product_id])) {
        // Si el producto no está en el carrito, lo agregamos
        $_SESSION["cart"][$product_id] = [
            "quantity" => $quantity,
            "unit"     => $unit
        ];
    } else {
        // Si ya existe, sumar la cantidad y actualizar la unidad (si corresponde)
        $_SESSION["cart"][$product_id]["quantity"] += $quantity;
        $_SESSION["cart"][$product_id]["unit"] = $unit;
    }
}

// Redirigir: si se envía "redirect_product_id" redirige a la página del producto, 
// de lo contrario redirige al carrito.
if (isset($_GET['redirect_product_id'])) {
    $redirect_id = intval($_GET['redirect_product_id']);
    header("Location: index.php?view=post&product_id=" . $redirect_id);
    exit;
} else {
    header("Location: index.php?view=cart");
    exit;
}
?>
