<?php
  $page_title = 'All Sales';
  require_once('includes/auth_check.php');
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>

  $sales = find_all_sale();

  include_once('layouts/header.php'); 
?>

<!-- Campo de búsqueda para filtrado en el cliente -->

<div class="row" style="margin-bottom: 20px;">
  <div class="col-md-12">
    <input id="searchInput" type="text" class="form-control" placeholder="Filter by item, user, location, etc.">
  </div>
</div>

<!-- Contenido principal de la página -->
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); // Mensajes de éxito/error ?>
  </div>
  
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <div class="pull-right">
          <a href="add_sale.php" class="btn btn-primary">Add Input/Output</a>
        </div>
      </div>
      
      <div class="panel-body">
        <table id="salesTable" class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Name of item</th>
              <th class="text-center" style="width: 10%;">Quantity</th>
              <th class="text-center" style="width: 15%;">Users</th>
              <th class="text-center" style="width: 15%;">Date</th>
              <th class="text-center" style="width: 15%;">Location</th>
              <th class="text-center" style="width: 10%;">Type</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($sales as $sale): ?>
              <tr>
                <td class="text-center"><?php echo count_id(); ?></td>
                <td><?php echo remove_junk($sale['product_name']); ?></td>
                <td class="text-center"><?php echo (int)$sale['qty']; ?></td>
                <td class="text-center"><?php echo remove_junk($sale['user_name']); ?></td>
                <td class="text-center"><?php echo read_date($sale['date']); ?></td>
                <td class="text-center"><?php echo remove_junk($sale['location']); ?></td>
                <td class="text-center">
                  <?php 
                    if($sale['status'] == 1):
                      echo '<span class="label label-success">Input</span>';
                    elseif($sale['status'] == 0):
                      echo '<span class="label label-danger">Output</span>';
                    elseif($sale['status'] == 2):
                      echo '<span class="label label-info">Return</span>';
                    else:
                      echo '<span class="label label-default">Not defined</span>';
                    endif;
                  ?>
                </td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_sale.php?id=<?php echo (int)$sale['id'];?>" class="btn btn-info btn-xs" title="Editar" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_sale.php?id=<?php echo (int)$sale['id'];?>" class="btn btn-danger btn-xs" title="Eliminar" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div><!-- /.panel-body -->
    </div><!-- /.panel panel-default -->
  </div><!-- /.col-md-12 -->
</div><!-- /.row -->

<script>
  $(document).ready(function(){
    $("#searchSalesInput").on("keyup", function() {
      // Obtiene el valor ingresado y lo convierte a minúsculas
      var value = $(this).val().toLowerCase().trim();
      // Recorre cada fila del tbody de la tabla
      $("#salesTable tbody tr").each(function() {
        var rowText = $(this).text().toLowerCase();
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
