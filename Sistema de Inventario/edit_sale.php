<?php
  $page_title = 'Edit Sale';
  require_once('auth_check.php');
  //Check what level user has permission to view this page
  page_require_level(3);
?>

  // Obtener el registro 'sale' por ID
  $sale = find_by_id('sales', (int)$_GET['id']);
  if (!$sale) {
    $session->msg("d", "Movement ID not found.");
    redirect('sales.php');
  }

  // Obtener el producto relacionado
  $product = find_by_id('products', $sale['product_id']);
  if (!$product) {
    $session->msg("d", "Product not found.");
    redirect('sales.php');
  }
?>

<?php
if (isset($_POST['update_sale'])) {
    // Agregamos 'location' a los campos requeridos
    $req_fields = array('product_id', 'quantity', 'status', 'date', 'location');
    validate_fields($req_fields);

    if (empty($errors)) {
        $p_id       = (int)$db->escape($_POST['product_id']);
        $s_qty      = (int)$db->escape($_POST['quantity']);
        $status_text = $db->escape($_POST['status']);
        $s_date     = (!empty($_POST['date'])) ? $db->escape($_POST['date']) : make_date();
        $s_location = $db->escape($_POST['location']);

        // Convertir el estado a número:
        // "Entrada"     => 1  
        // "Salida"      => 0  
        // "Devolución"  => 2
        switch($status_text) {
            case "Input":
                $s_status = 1;
                break;
            case "Output":
                $s_status = 0;
                break;
            case "Return":
                $s_status = 2;
                break;
            default:
                $s_status = 1;
                break;
        }

        // Validaciones según el tipo de movimiento
        if ($s_status === 0) { // Salida
            // Obtener stock actual del producto
            $sql_check_stock = "SELECT quantity FROM products WHERE id = '{$p_id}' LIMIT 1";
            $result_stock = $db->query($sql_check_stock);
            $product_stock = $db->fetch_assoc($result_stock);

            if ($s_qty > (int)$product_stock['quantity']) {
                $session->msg('d', "Error: You cannot withdraw more than what is in stock. Available stock: " . $product_stock['quantity']);
                redirect('edit_sale.php?id=' . (int)$sale['id'], false);
            }
        } elseif ($s_status === 2) { // Devolución
            /* 
              Para la devolución se debe comprobar que la cantidad a devolver no exceda lo que se
              sacó previamente. Se excluye el registro que se está editando para obtener los totales actuales.
            */
            $sql_borrowed = "SELECT 
                                IFNULL(SUM(CASE WHEN status = 0 THEN qty ELSE 0 END),0) as total_output,
                                IFNULL(SUM(CASE WHEN status = 2 THEN qty ELSE 0 END),0) as total_return
                             FROM sales
                             WHERE product_id = '{$p_id}' 
                               AND user_id = '{$sale['user_id']}' 
                               AND id != '{$sale['id']}'";
            $result_borrowed  = $db->query($sql_borrowed);
            $borrowed_data    = $db->fetch_assoc($result_borrowed);
            $total_salida     = (int)$borrowed_data['total_output'];
            $total_devolucion = (int)$borrowed_data['total_return'];
            $max_devolucion   = $total_salida - $total_devolucion;

            if ($s_qty > $max_devolucion) {
                $session->msg('d', "Error: You cannot return more items than you took out. Maximum allowed:" . $max_devolucion);
                redirect('edit_sale.php?id=' . (int)$sale['id'], false);
            }
        }

        // Actualizar el registro de movimiento (incluye location)
        $sql  = "UPDATE sales SET ";
        $sql .= " product_id='{$p_id}',";
        $sql .= " qty='{$s_qty}',";
        $sql .= " status='{$s_status}',";
        $sql .= " date='{$s_date}',";
        $sql .= " location='{$s_location}'";
        $sql .= " WHERE id='{$sale['id']}'";

        if ($db->query($sql)) {
            // Actualizar el stock
            // Nota: en una edición se debería revertir el efecto del registro anterior
            // y aplicar el nuevo, pero aquí se usa una actualización simple.
            update_product_qty($s_qty, $p_id, $s_status);

            $session->msg('s', "Updated movement.");
            redirect('sales.php', false);
        } else {
            $session->msg('d', 'Sorry, the update failed.');
            redirect('edit_sale.php?id=' . (int)$sale['id'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_sale.php?id=' . (int)$sale['id'], false);
    }
}
?>

<?php include_once('layouts/header.php'); ?>
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
            <span>Edit Input/output/Return</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="edit_sale.php?id=<?php echo (int)$sale['id']; ?>" class="clearfix">
            
            <!-- Selección de Producto -->
            <div class="form-group">
              <label for="product_id">Select Item</label>
              <select class="form-control" name="product_id" required>
                <option value="">Select an item</option>
                <?php
                  $all_products = find_all('products');
                  foreach ($all_products as $prod):
                ?>
                <option value="<?php echo (int)$prod['id']; ?>" 
                  <?php if($sale['product_id'] == $prod['id']) echo "selected"; ?>>
                  <?php echo $prod['name']; ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Cantidad -->
            <div class="form-group">
              <label for="quantity">Quantity</label>
              <input type="number" class="form-control" name="quantity" 
                     value="<?php echo (int)$sale['qty']; ?>" required>
            </div>

            <!-- Estado (Entrada, Salida, Devolución) -->
            <div class="form-group">
              <label for="status">Status</label>
              <select class="form-control" name="status" required>
                <option value="Entrada" <?php if ($sale['status'] == 1) echo "selected"; ?>>Input</option>
                <option value="Salida"  <?php if ($sale['status'] == 0) echo "selected"; ?>>Output</option>
                <option value="Devolución" <?php if ($sale['status'] == 2) echo "selected"; ?>>Return</option>
              </select>
            </div>

            <!-- Fecha -->
            <div class="form-group">
              <label for="date">Dates</label>
              <input type="date" class="form-control" name="date" 
                     value="<?php echo remove_junk($sale['date']); ?>" required>
            </div>

            <!-- Ubicación -->
            <div class="form-group">
              <label for="location">Location</label>
              <input type="text" class="form-control" name="location" 
                     value="<?php echo remove_junk($sale['location']); ?>" required>
            </div>

            <!-- Botón de actualización -->
            <button type="submit" name="update_sale" class="btn btn-primary">
            Update Movement
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
