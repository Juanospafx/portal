<?php
  $page_title = 'Edit Brand';
  require_once('includes/auth_check.php');
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
?>
<?php
  //Display all catgories.
  $brand = find_by_id('brand',(int)$_GET['id']);
  if(!$brand){
    $session->msg("d","Missing Brand id.");
    redirect('brand.php');
  }
?>

<?php
if(isset($_POST['edit_cat'])){
  $req_field = array('brand-name');
  validate_fields($req_field);
  $cat_name = remove_junk($db->escape($_POST['brand-name']));
  if(empty($errors)){
        $sql = "UPDATE brand SET name='{$cat_name}'";
       $sql .= " WHERE id='{$brand['id']}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Brand actualizada con éxito.");
       redirect('brand.php',false);
     } else {
       $session->msg("d", "Lo siento, actualización falló.");
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
   <div class="col-md-5">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <span>Edit <?php echo remove_junk(ucfirst($brand['name']));?></span>
        </strong>
       </div>
       <div class="panel-body">
         <form method="post" action="edit_brand.php?id=<?php echo (int)$brand['id'];?>">
           <div class="form-group">
               <input type="text" class="form-control" name="brand-name" value="<?php echo remove_junk(ucfirst($brand['name']));?>">
           </div>
           <button type="submit" name="edit_cat" class="btn btn-primary">Update Brand</button>
       </form>
       </div>
     </div>
   </div>
</div>



<?php include_once('layouts/footer.php'); ?>
