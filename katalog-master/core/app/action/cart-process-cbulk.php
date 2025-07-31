<?php
session_start();
require_once "../../core/autoload.php"; // Ajusta la ruta según tu estructura

// Verificar que se hayan enviado productos seleccionados
if (!isset($_GET['selected_items']) || !is_array($_GET['selected_items'])) {
    die("Error: No products selected for bulk addition.");
}

// Obtener cantidad y unidad (aplicables a todos)
$quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1;
$unit = isset($_GET['unit']) ? $_GET['unit'] : 'ea';

// Inicializar el carrito si no existe
if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

// Iterar sobre los IDs de productos seleccionados
foreach ($_GET['selected_items'] as $pid) {
    $post_id = intval($pid);

    // Opcional: verificar que el producto existe
    $product = PostData::getById($post_id);
    if (!$product) {
        continue; // O manejar error según prefieras
    }

    if (!isset($_SESSION["cart"][$post_id])) {
        $_SESSION["cart"][$post_id] = [
            "quantity" => $quantity,
            "unit"     => $unit
        ];
    } else {
        $_SESSION["cart"][$product_id]["quantity"] += $quantity;
        $_SESSION["cart"][$product_id]["unit"] = $unit;
    }
}

// Redirigir al carrito
header("Location: ../../index.php?view=cart");
exit;
?>
