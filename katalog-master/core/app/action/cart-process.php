<?php
session_start();
require_once "core/autoload.php"; // Ajusta la ruta según tu estructura

// Para depuración temporal (descomenta para ver qué llega)
// echo "<pre>"; print_r($_GET); echo "</pre>"; exit;

// Inicializar el carrito en la sesión si no existe
if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

$product_ids = [];  // Array que contendrá los IDs a agregar

// Procesamiento Bulk: revisamos si se envió "items"
if (isset($_GET['items']) && is_array($_GET['items'])) {
    // Iteramos sobre cada item del array
    foreach ($_GET['items'] as $key => $item) {
        // Solo procesamos si está marcado (selected == 1)
        if (!isset($item['selected']) || $item['selected'] != 1) {
            continue;
        }
        // Verificamos que exista 'post_id'
        if (!isset($item['post_id'])) {
            // Para depuración: podemos registrar error (luego eliminar)
            // echo "Error: No se proporcionó post_id para el item con key $key<br>";
            continue;
        }
        $pid = intval($item['post_id']);
        $product_ids[] = $pid;
    }
} 
// Procesamiento individual: si no se envió "items" pero sí "product_id"
elseif (isset($_GET['product_id']) && !empty($_GET['product_id'])) {
    $product_ids[] = intval($_GET['product_id']);
}

// Si no se encontraron IDs, redirigir al carrito sin hacer nada
if (empty($product_ids)) {
    header("Location: index.php?view=cart");
    exit;
}

// Obtener cantidad y unidad global (para bulk o individual)
$global_quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;
$global_unit     = isset($_GET['unit']) ? $_GET['unit'] : 'ea';

// Agregar cada producto al carrito
foreach ($product_ids as $pid) {
    if (!isset($_SESSION["cart"][$pid])) {
        $_SESSION["cart"][$pid] = [
            "quantity" => $global_quantity,
            "unit"     => $global_unit
        ];
    } else {
        $_SESSION["cart"][$pid]["quantity"] += $global_quantity;
        $_SESSION["cart"][$pid]["unit"] = $global_unit;
    }
}

// Redirigir: si se envió "redirect_product_id", redirige a la página del producto actual; 
// de lo contrario, redirige al carrito.
if (isset($_GET['redirect_product_id'])) {
    $redirect_id = intval($_GET['redirect_product_id']);
    header("Location: index.php?view=post&product_id=" . $redirect_id);
    exit;
} else {
    header("Location: index.php?view=post&product_id=" . $redirect_id);
    exit;
}
?>
