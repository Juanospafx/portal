<?php
require_once('includes/load.php');

// Manejar la búsqueda de productos por código de barras
if (isset($_GET['barcode'])) {
    $barcode = remove_junk($db->escape($_GET['barcode']));
    $product = find_product_by_barcode($barcode); // Asumiendo que esta función ya existe y funciona
    
    if ($product) {
        echo json_encode($product);
    } else {
        echo json_encode(array('error' => 'Product not found'));
    }
    return; // Terminar el script para evitar otros bloques
}

// Manejar la búsqueda de productos por nombre
if (isset($_GET['product_name']) && strlen($_GET['product_name'])) {
    $product_name = remove_junk($db->escape($_GET['product_name']));
    $html = '';
    if ($results = find_product_by_title($product_name)) {
        foreach ($results as $result) {
            $html .= "<li>";
            $html .= "  <a href=\"#\">";
            $html .= "    <div class=\"pull-left\">";
            if ($result['media_id'] === '0') {
                $html .= "      <img class=\"img-avatar img-circle\" src=\"uploads/products/no_image.jpg\" alt=\"\">";
            } else {
                $media = find_by_id('media', (int)$result['media_id']);
                $html .= "      <img class=\"img-avatar img-circle\" src=\"uploads/products/" . $media['file_name'] . "\" alt=\"\">";
            }
            $html .= "    </div>";
            $html .= "    <div class=\"body\">";
            $html .= "      <p class=\"text-muted\">" . $result['name'] . "</p>";
            $html .= "    </div>";
            $html .= "  </a>";
            $html .= "</li>";
        }
    } else {
        $html .= "<li><a href=\"#\">No products found</a></li>";
    }
    echo $html;
    return;
}

// Manejar la búsqueda de productos por ubicación
if (isset($_GET['location']) && strlen($_GET['location'])) {
    $location = remove_junk($db->escape($_GET['location']));
    $products = find_products_by_location($location);
    echo json_encode($products);
    return;
}
?>