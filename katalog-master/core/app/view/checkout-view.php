<?php
session_start();
ob_start(); // ✅ Evita salida previa de datos

// ✅ Verificar que el usuario esté autenticado antes de procesar la compra
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php"); // Redirigir al login si no está autenticado
    exit;
}

// ✅ Verificar que el carrito no esté vacío antes de continuar
if (!isset($_SESSION["cart"]) || empty($_SESSION["cart"])) {
    echo "<script>alert('El carrito está vacío.'); window.location='index.php?view=cart';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmar Compra</title>
    <link rel="stylesheet" type="text/css" href="res/lib/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3 text-center mt-5">
                <h2 class="mb-4">Confirmar Compra</h2>
                <p>¿Estás seguro de que deseas finalizar la compra?</p>

                <!-- Botón para finalizar la compra -->
                <form action="core/app/action/checkout.php" method="POST">
                    <button type="submit" class="btn btn-success btn-lg">Sí, finalizar compra</button>
                </form>

                <!-- Botón para volver al carrito -->
                <a href="index.php?view=cart" class="btn btn-secondary mt-3">Volver al carrito</a>
            </div>
        </div>
    </div>

    <script src="res/lib/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
