<?php
  $page_title = 'All Product';
  require_once('auth_check.php');
  // Check what level user has permission to view this page
  page_require_level(2);
  $products = join_product_table();
?>
<?php include_once('layouts/header.php'); ?>

<!-- Campo de búsqueda para filtrado en el cliente -->
<div class="row" style="margin-bottom: 20px;">
  <div class="col-md-12">
    <input id="searchInput" type="text" class="form-control" placeholder="Filter by name, brand, etc.">
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <div class="pull-right">
          <a href="add_product.php" class="btn btn-primary">Add Item</a>
        </div>
      </div>
      <div class="panel-body">
        <table id="productsTable" class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Image</th>
              <th>Name</th>
              <th class="text-center" style="width: 10%;">Shelf</th>
              <th class="text-center" style="width: 10%;">Stock</th>
              <th class="text-center" style="width: 10%;">Color</th>
              <th class="text-center" style="width: 10%;">Brand</th>
              <th class="text-center" style="width: 10%;">Attache</th>
              <th class="text-center">Barcode</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php 
              $i = 1;
              foreach ($products as $product): 
            ?>
            <tr>
              <td class="text-center"><?php echo $i++; ?></td>
              <td>
                <?php if(empty($product['image'])): ?>
                  <img class="img-avatar img-circle" src="uploads/products/no_image.jpg" alt="Sin imagen">
                <?php else: ?>
                  <img class="img-avatar img-circle" src="uploads/products/<?php echo $product['image']; ?>" alt="Imagen del producto">
                <?php endif; ?>
              </td>
              <td><?php echo remove_junk($product['name']); ?></td>
              <td class="text-center"><?php echo remove_junk($product['categorie']); ?></td>
              <td class="text-center"><?php echo remove_junk($product['quantity']); ?></td>
              <td class="text-center"><?php echo remove_junk($product['color']); ?></td>
              <td class="text-center"><?php echo remove_junk($product['brand']); ?></td>
              <td class="text-center"><?php echo read_date($product['date']); ?></td>
              <td class="text-center">
                <?php if (!empty($product['barcode'])): ?>
                  <img src="<?php echo $product['barcode']; ?>" alt="Barcode" style="width: 100px;">
                  <a href="<?php echo $product['barcode']; ?>" class="btn btn-success btn-xs" download>Download</a>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <div class="btn-group">
                  <a href="edit_product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-info btn-xs" title="Editar" data-toggle="tooltip">
                    <span class="glyphicon glyphicon-edit"></span>
                  </a>
                  <a href="delete_product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-danger btn-xs" title="Eliminar" data-toggle="tooltip">
                    <span class="glyphicon glyphicon-trash"></span>
                  </a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Script de filtrado en el cliente -->
<script>
  $(document).ready(function(){
    $("#searchInput").on("keyup", function() {
      // Obtiene el valor ingresado y lo convierte a minúsculas
      var value = $(this).val().toLowerCase().trim();
      // Recorre cada fila del tbody de la tabla
      $("#productsTable tbody tr").each(function() {
        var rowText = $(this).text().toLowerCase();
        // Debug: imprime en consola para ver el contenido evaluado
        // console.log("Row text:", rowText);
        if (rowText.indexOf(value) === -1) {
          $(this).hide();
        } else {
          $(this).show();
        }
      });
    });
  });
</script>

<?php include_once('layouts/footer.php'); ?>
