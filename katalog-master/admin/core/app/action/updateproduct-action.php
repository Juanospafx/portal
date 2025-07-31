<?php

$product = new PostData();
$base = new Database();
$con = $base->connect();

// Recorrer y limpiar todos los datos enviados por POST
foreach ($_POST as $k => $v) {
    $clean_value = mysqli_real_escape_string($con, $v);
    $product->$k = $clean_value;
}

// Manejo de imagen (si se sube una nueva)
$handle = new Upload($_FILES['image']);
if ($handle->uploaded) {
    $url = "storage/products/";
    $handle->Process($url);
    if ($handle->processed) {
        $product->image = $handle->file_dst_name;
        $product->update_image();
    }
}

// Verificar checkboxes
$product->is_public   = isset($_POST["is_public"]) ? 1 : 0;
$product->is_featured = isset($_POST["is_featured"]) ? 1 : 0;

// Ejecutar la actualización del producto
$product->update();

// Obtener el ID del producto (se asume que se envió en el formulario y/o asignado en el foreach)
$product_id = $product->id;  // También podrías usar $_POST["id"]

// Procesar la relación opcional de subcategoría
if (isset($_POST["subcategory_id"])) {
    // Limpiar el valor recibido
    $subcat = mysqli_real_escape_string($con, $_POST["subcategory_id"]);
    if (!empty($subcat)) {
        // Verificar si ya existe una relación para este producto
        $existingRelation = ProductSubcategoriesData::getByProductId($product_id);
        if ($existingRelation) {
            // Actualizar la relación existente
            $relation = new ProductSubcategoriesData();
            $relation->post_id = $product_id;
            $relation->subcategory_id = $subcat;
            $relation->update();
        } else {
            // Insertar una nueva relación
            $relation = new ProductSubcategoriesData();
            $relation->post_id = $product_id;
            $relation->subcategory_id = $subcat;
            $relation->add();
        }
    } else {
        // Si se envía vacío, eliminar cualquier relación existente
        ProductSubcategoriesData::deleteByPostId($product_id);
    }
}

// Establecer mensaje de sesión para notificar éxito
$_SESSION["product_updated"] = 1;

// Redirigir a la lista de productos
Core::redir("index.php?view=posts");
?>
