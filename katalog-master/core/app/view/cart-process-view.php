<?php
session_start();
require_once "core/autoload.php"; // Ajusta la ruta según tu estructura

// Inicializar el carrito si no existe
if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

/* 
  Determinar el ID de redirección para volver a la página del producto actual.
  Se busca primero en "redirect_product_id", luego en "product_id" y, finalmente,
  se puede almacenar en sesión (por ejemplo, si lo asignas en post-view.php).
*/
$redirect_id = null;
if (isset($_GET['redirect_product_id']) && !empty($_GET['redirect_product_id'])) {
    $redirect_id = (int) $_GET['redirect_product_id'];
} elseif (isset($_GET['product_id']) && !empty($_GET['product_id'])) {
    $redirect_id = (int) $_GET['product_id'];
} elseif (isset($_SESSION['current_product_id'])) {
    $redirect_id = (int) $_SESSION['current_product_id'];
}

// 1. Procesar productos relacionados (formulario que envía "items")
if (isset($_GET['items']) && is_array($_GET['items'])) {
    foreach ($_GET['items'] as $id => $item) {
        // Procesar solo los ítems marcados
        if (isset($item['selected']) && $item['selected'] == 1) {
            // Se espera que se envíe "post_id" (definido en el formulario de productos relacionados)
            if (!isset($item['post_id'])) {
                echo "Error: No se proporcionó post_id para el item con id " . htmlspecialchars($id);
                exit;
            }
            $product_id = (int) $item['post_id'];
            // Usar la cantidad y unidad específicos de cada item, o valor por defecto si no se envían
            $quantity = isset($item['quantity']) ? (int) $item['quantity'] : 1;
            $unit     = isset($item['unit']) ? $item['unit'] : 'ea';

            // Agregar o actualizar el producto en el carrito
            if (!isset($_SESSION["cart"][$product_id])) {
                $_SESSION["cart"][$product_id] = [
                    "quantity" => $quantity,
                    "unit"     => $unit
                ];
            } else {
                $_SESSION["cart"][$product_id]["quantity"] += $quantity;
                $_SESSION["cart"][$product_id]["unit"] = $unit;
            }
        }
    }

// 2. Procesar producto individual (formulario principal)
} elseif (isset($_GET['product_id']) && !empty($_GET['product_id'])) {
    $product_id = (int) $_GET['product_id'];
    $quantity   = isset($_GET['quantity']) ? (int) $_GET['quantity'] : 1;
    $unit       = isset($_GET['unit']) ? $_GET['unit'] : 'ea';

    if (!isset($_SESSION["cart"][$product_id])) {
        $_SESSION["cart"][$product_id] = [
            "quantity" => $quantity,
            "unit"     => $unit
        ];
    } else {
        $_SESSION["cart"][$product_id]["quantity"] += $quantity;
        $_SESSION["cart"][$product_id]["unit"] = $unit;
    }

// 3. Si no se envía ni "items" ni "product_id", redirigir a la página principal
} else {
    header("Location: index.php");
    exit;
}

// Redirigir: si se pudo determinar el producto actual, volver a la vista del producto;
// de lo contrario, ir a la vista del carrito (en este ejemplo, se redirige a la vista del producto).
if ($redirect_id !== null) {
    header("Location: index.php?view=post&product_id=" . $redirect_id);
    exit;
} else {
    header("Location: index.php?view=post&product_id=" . $redirect_id);
    exit;
}
?>

