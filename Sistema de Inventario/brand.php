<?php
  $page_title = 'All Brand';
  require_once('includes/auth_check.php');
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
  $all_brand = find_all('brands');
?>
<?php
 if(isset($_POST['add_cat'])){
   $req_field = array('brand-name');
   validate_fields($req_field);
   $cat_name = remove_junk($db->escape($_POST['brand-name']));
   if(empty($errors)){
      $sql  = "INSERT INTO brand (name)";
      $sql .= " VALUES ('{$cat_name}')";
      if($db->query($sql)){
        $session->msg("s", "Brand agregada exitosamente.");
        redirect('brand.php',false);
      } else {
        $session->msg("d", "Lo siento, registro falló");
        redirect('brand.php',false);
      }
   } else {
     $session->msg("d", $errors);
     redirect('brand.php',false);
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
    <div class="col-md-5">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Add brand</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="brand.php">
            <div class="form-group">
                <input type="text" class="form-control" name="brand-name" placeholder="Brand name" required>
            </div>
            <button type="submit" name="add_cat" class="btn btn-primary">Add Brand</button>
        </form>
        </div>
      </div>
    </div>
    <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Brand List</span>
       </strong>
      </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>Brands</th>
                    <th class="text-center" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($all_brand as $cat):?>
                <tr>
                    <td class="text-center"><?php echo count_id();?></td>
                    <td><?php echo remove_junk(ucfirst($cat['name'])); ?></td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="edit_brand.php?id=<?php echo (int)$cat['id'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Editar">
                          <span class="glyphicon glyphicon-edit"></span>
                        </a>
                        <a href="delete_brand.php?id=<?php echo (int)$cat['id'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Eliminar">
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
  </div>
  <?php include_once('layouts/footer.php'); ?>
