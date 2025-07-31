<?php


$product = new PostData();
$base = new Database();
// Usamos la conexión que se usará en la inserción
$con = $base->connect();

foreach ($_POST as $k => $v) {
    // Limpiar y escapar cada valor
    $clean_value = mysqli_real_escape_string($con, $v);
    $product->$k = $clean_value;
}

// Generar código aleatorio para short_name
$alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWYZ1234567890_-";
$code = "";
for ($i = 0; $i < 11; $i++) {
    $code .= $alphabet[rand(0, strlen($alphabet) - 1)];
}
$product->short_name = $code;

// Manejo de imagen con la clase Upload
$handle = new Upload($_FILES['image']);
if ($handle->uploaded) {
    $url = "storage/products/";
    $handle->Process($url);
    if ($handle->processed) {
        $product->image = $handle->file_dst_name;
    }
}

// Definir valores booleanos
$product->is_public   = isset($_POST["is_public"]) ? 1 : 0;
$product->in_existence = isset($_POST["in_existence"]) ? 1 : 0;
$product->is_featured  = isset($_POST["is_featured"]) ? 1 : 0;

// Guardar el producto en la tabla "post"
$product->add();

// Obtener el ID insertado usando la misma conexión global
$product_id = mysqli_insert_id(Database::getCon());

// Procesar la relación opcional de subcategoría
if (isset($_POST["subcategory_id"]) && !empty($_POST["subcategory_id"])) {
    $relation = new ProductSubcategoriesData();
    $relation->post_id = $product_id;
    $relation->subcategory_id = mysqli_real_escape_string(Database::getCon(), $_POST["subcategory_id"]);
    $relation->add();
}

// Redirigir a la vista de productos
Core::redir("index.php?view=posts");
?>
