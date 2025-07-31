<?php
if (!empty($_POST)) {
    // Obtener la subcategoría a actualizar utilizando el identificador "subcategory_id"
    $subcategory = SubcategoryData::getById($_POST["subcategory_id"]);
    
    // Validar que se encontró la subcategoría
    if ($subcategory == null) {
        echo "Error: Subcategoría no encontrada.";
        exit;
    }
    
    // Actualizar propiedades con los datos enviados por POST
    $subcategory->name = $_POST["name"];
    $subcategory->short_name = $_POST["short_name"];
    $subcategory->category_id = $_POST["category_id"];
    $subcategory->is_active = isset($_POST["is_active"]) ? 1 : 0;
    
    // Ejecutar la actualización en la base de datos
    $subcategory->update();
    
    // Redirigir a la vista de subcategorías
    Core::redir("index.php?view=subcategories");
    exit;
}
?>
