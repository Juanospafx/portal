<?php
  $page_title = 'Add Sale';
  require_once('includes/auth_check.php');
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>

// Listado de productos
$all_products = find_all('products');
// Listado de usuarios (si lo requieres)
$all_users = find_all('users'); 
$all_locations = find_by_sql("SELECT DISTINCT location FROM sales");

if(isset($_POST['add_sale'])){
  // Se requieren estos campos
  $req_fields = array('product_id', 'quantity', 'status', 'date', 'location');
  validate_fields($req_fields);
  
  if(empty($errors)){
      // Recoger y convertir datos
      $p_id       = (int)$db->escape($_POST['product_id']);
      $s_qty      = (int)$db->escape($_POST['quantity']);
      $user_id    = (int)$_SESSION['user_id'];
      $s_status   = (int)$db->escape($_POST['status']); // 1: Entrada, 0: Salida, 2: Devolución
      $s_date     = (!empty($_POST['date'])) ? date("Y-m-d", strtotime($_POST['date'])) : make_date();
      $s_location = $db->escape($_POST['location']);
      
      // Validaciones según el tipo de movimiento
      if($s_status === 0){ // Salida
          // Verificar que no se retire más de lo que hay en stock
          $sql_check_stock = "SELECT quantity FROM products WHERE id = '{$p_id}' LIMIT 1";
          $result_stock    = $db->query($sql_check_stock);
          $product_stock   = $db->fetch_assoc($result_stock);
          if($s_qty > (int)$product_stock['quantity']){
              $_SESSION['form_data'] = $_POST;
              $session->msg('d', "Error: You cannot withdraw more than what is in stock. Available stock: " . $product_stock['quantity']);
              redirect('add_sale.php', false);
          }
      } elseif($s_status === 2){ // Devolución
          $sql_borrowed = "SELECT 
                              IFNULL(SUM(CASE WHEN status = 0 THEN qty ELSE 0 END),0) as total_output,
                              IFNULL(SUM(CASE WHEN status = 2 THEN qty ELSE 0 END),0) as total_return
                           FROM sales
                           WHERE product_id = '{$p_id}' AND user_id = '{$user_id}'";
          $result_borrowed  = $db->query($sql_borrowed);
          $borrowed_data    = $db->fetch_assoc($result_borrowed);
          $total_salida     = (int)$borrowed_data['total_output'];
          $total_devolucion = (int)$borrowed_data['total_return'];
          $max_devolucion   = $total_salida - $total_devolucion;
          
          if($s_qty > $max_devolucion){
              $_SESSION['form_data'] = $_POST;
              $session->msg('d', "Error: You cannot return more items than you took out. Maximum allowed: " . $max_devolucion);
              redirect('add_sale.php', false);
          }
      }
      
      // Insertar el registro de movimiento
      $sql  = "INSERT INTO sales (product_id, qty, user_id, status, date, location) VALUES 
              ('{$p_id}', '{$s_qty}', '{$user_id}', '{$s_status}', '{$s_date}', '{$s_location}')";
      if($db->query($sql)){
          update_product_qty($s_qty, $p_id, $s_status);
          $session->msg('s', "Added inventory movement.");
          redirect('add_sale.php', false);
      } else {
          $_SESSION['form_data'] = $_POST;
          $session->msg('d', 'Sorry, registration failed.');
          redirect('add_sale.php', false);
      }
  } else {
      $_SESSION['form_data'] = $_POST;
      $session->msg("d", $errors);
      redirect('add_sale.php', false);
  }
}
?>

<?php include_once('layouts/header.php'); ?>

<?php
// Recuperar datos del formulario almacenados en sesión (si existen) y luego limpiarlos
$form_data = array();
if(isset($_SESSION['form_data'])){
  $form_data = $_SESSION['form_data'];
  unset($_SESSION['form_data']);
}
?>

<!-- Agrega la librería html5-qrcode -->
<script src="https://unpkg.com/html5-qrcode"></script>

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
            <span>Add input/output/Return</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="add_sale.php" class="clearfix">
              <!-- Fila para Barcode y selección de producto -->
              <div class="form-group">
                <div class="row">
                  <!-- Campo de código de barras -->
                  <div class="col-md-6">
                    <label for="barcode">Barcode (optional)</label>
                    <input type="text" id="barcode" name="barcode" class="form-control" placeholder="Scan barcode or enter manually">
                    <small class="form-text text-muted">Scan a barcode to automatically fill in the product information.</small>
                  </div>
                  <!-- Botón para iniciar el escaneo -->
                  <div class="col-md-6">
                    <label>&nbsp;</label>
                    <button id="start-scan" type="button" class="btn btn-info btn-block">Scan Barcode</button>
                  </div>
                </div>
              </div>
              
              <!-- Contenedor para mostrar la cámara al escanear -->
              <div class="form-group" id="scanner-container" style="display:none;">
                <div id="reader" style="width:300px; height:300px; margin: 0 auto;"></div>
              </div>

              <!-- Producto: Se selecciona automáticamente si se escanea el código -->
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <label for="product_id">Select item</label>
                    <select class="form-control" name="product_id" id="product_id" required>
                      <option value="">Select an item</option>
                      <?php foreach ($all_products as $product): ?>
                        <option value="<?php echo (int)$product['id']; ?>">
                          <?php echo $product['name']; ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>

              <!-- Cantidad -->
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <label for="quantity">Quantity</label>
                    <input type="number" class="form-control" name="quantity" placeholder="Cantidad" required 
                           value="<?php echo isset($form_data['quantity']) ? htmlspecialchars($form_data['quantity']) : ''; ?>">
                  </div>
                  <!-- Estado -->
                  <div class="col-md-6">
                    <label for="status">Status</label>
                    <select class="form-control" name="status" required>
                      <option value="">Select a Status</option>
                      <option value="1" <?php echo (isset($form_data['status']) && $form_data['status'] == 1) ? 'selected' : ''; ?>>Input</option>
                      <option value="0" <?php echo (isset($form_data['status']) && $form_data['status'] == 0) ? 'selected' : ''; ?>>Output</option>
                      <option value="2" <?php echo (isset($form_data['status']) && $form_data['status'] == 2) ? 'selected' : ''; ?>>Return</option>
                    </select>
                  </div>
                </div>
              </div>

              <!-- Fecha y Ubicación -->
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <label for="date">Date</label>
                    <input type="date" class="form-control" name="date" 
                           value="<?php echo isset($form_data['date']) ? htmlspecialchars($form_data['date']) : ''; ?>">
                  </div>
                  <div class="col-md-6">
                    <label for="location">Location</label>
                    <select class="form-control" name="location" required>
                      <option value="">Select a Location</option>
                      <?php foreach ($all_locations as $location): ?>
                        <option value="<?php echo $location['location']; ?>">
                          <?php echo $location['location']; ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>

              <button type="submit" name="add_sale" class="btn btn-danger">Add movement</button>
          </form>
         </div>
        </div>
      </div>
    </div>
  </div>

<!-- Script para iniciar el escaneo con html5-qrcode -->
<script>
document.getElementById('start-scan').addEventListener('click', function(){
  // Mostrar el contenedor del escáner
  document.getElementById('scanner-container').style.display = 'block';
  const html5QrCode = new Html5Qrcode("reader");
  const config = { fps: 10, qrbox: 250 };

  html5QrCode.start(
    { facingMode: "environment" },
    config,
    (decodedText, decodedResult) => {
      // Cuando se detecta el código, asignarlo al input de barcode
      console.log("Barcode detected: " + decodedText);
      document.getElementById('barcode').value = decodedText;
      // Detener el escáner y ocultar el contenedor
      html5QrCode.stop().then(() => {
        document.getElementById('scanner-container').style.display = 'none';
        // Rellenar automáticamente el producto
        var barcode = document.getElementById('barcode').value;
        if (barcode) {
            fetch('ajax.php?barcode=' + barcode)
                .then(response => response.json())
                .then(data => {
                    if(data && !data.error){
                      document.getElementById('product_id').value = data.id;
                      document.getElementsByName('quantity')[0].value = 1;
                      document.getElementsByName('status')[0].value = 0;
                    } else {
                      alert('Product not found for barcode: ' + barcode);
                    }
                })
                .catch(err => {
                    console.error("Error fetching product data: " + err);
                    alert('Error retrieving product information.');
                });
        }
      }).catch(err => {
        console.error("Error stopping scanner: " + err);
      });
    },
    errorMessage => {
      // Opcional: maneja errores en la lectura (por ejemplo, mostrar un mensaje en consola)
      // console.log("Scanning error: " + errorMessage);
    }
  ).catch(err => {
    console.error("Error starting scanner: " + err);
    alert('Error starting the camera. Please check permissions.');
  });
});

// Script para buscar producto por código de barras al cambiar el input manualmente
document.getElementById('barcode').addEventListener('change', function() {
    var barcode = this.value;
    var productSelect = document.getElementById('product_id');

    if (barcode) {
        fetch('ajax.php?barcode=' + barcode)
            .then(response => response.json())
            .then(data => {
                if(data && !data.error){
                  productSelect.value = data.id;
                } else {
                  alert('Product not found for barcode: ' + barcode);
                }
            })
            .catch(err => {
                console.error("Error fetching product data: " + err);
                alert('Error retrieving product information.');
            });
    } 
});
</script>

<?php include_once('layouts/footer.php'); ?>