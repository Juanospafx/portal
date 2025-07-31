<?php
  $page_title = 'Add Product';
  require_once('includes/auth_check.php');
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $all_categories = find_all('categories');
  $all_photo = find_all('media');
  $all_brands = find_all('brands');
?>

<?php include_once('layouts/header.php'); ?>

<?php
// Recuperar datos del formulario guardados en la sesión, si existen, y luego limpiarlos
$form_data = array();
if (isset($_SESSION['form_data'])) {
    $form_data = $_SESSION['form_data'];
    unset($_SESSION['form_data']);
}
?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="row">
  <div class="col-md-9">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Add Item</span>
          </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form id="add-product-form" method="post" action="add_product.php" class="clearfix" enctype="multipart/form-data">
              <!-- Nombre del producto -->
              <div class="form-group">
                <label>Item name:</label>
                <input type="text" class="form-control" name="product-title" placeholder="Name" maxlength="50"
                       value="<?php echo isset($form_data['product-title']) ? htmlspecialchars($form_data['product-title']) : ''; ?>">
              </div>

              <!-- Formulario de subida de múltiples imágenes -->
              <div class="form-group">
                <label>Upload images:</label>
                <input type="file" name="product-images[]" multiple class="form-control">
              </div>

              

              <!-- Selección de Anaquel -->
              <div class="form-group">
                <label>Shelf:</label>
                <select class="form-control" name="product-categorie">
                  <option value="">Select a Shelf</option>
                  <?php foreach ($all_categories as $cat): ?>
                    <?php $selected = (isset($form_data['product-categorie']) && $form_data['product-categorie'] == $cat['id']) ? 'selected' : ''; ?>
                    <option value="<?php echo (int)$cat['id']; ?>" <?php echo $selected; ?>>
                        <?php echo $cat['name']; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Brand selection -->
              <div class="form-group">
                <label>Brand:</label>
                <select class="form-control" name="product-brand">
                  <option value="">Select an Brand</option>
                  <?php foreach ($all_brand as $brand): ?>
                    <?php $selected = (isset($form_data['product-brand']) && $form_data['product-brand'] == $brand['id']) ? 'selected' : ''; ?>
                    <option value="<?php echo (int)$brand['id']; ?>" <?php echo $selected; ?>>
                        <?php echo $brand['name']; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Cantidad -->
              <div class="form-group">
                <label>Quantity:</label>
                <input type="number" class="form-control" name="product-quantity" placeholder="123..."
                       value="<?php echo isset($form_data['product-quantity']) ? htmlspecialchars($form_data['product-quantity']) : ''; ?>">
              </div>

              <!-- Color -->
              <div class="form-group">
                <label>Color:</label>
                <input type="text" class="form-control" name="product-color" placeholder="Red" maxlength="20"
                       value="<?php echo isset($form_data['product-color']) ? htmlspecialchars($form_data['product-color']) : ''; ?>">
              </div>

              <button type="submit" name="add_product" class="btn btn-danger">Add item</button>
          </form>
         </div>
        </div>
      </div>
  </div>
</div>



<?php include_once('layouts/footer.php'); ?>