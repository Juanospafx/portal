<?php
  $page_title = 'Edit product';
  require_once('auth_check.php');
  // Check what level user has permission to view this page
  page_require_level(2);
?>
  $all_categories = find_all('categories');
  $all_photo = find_all('media');
  $product = find_by_id('products',(int)$_GET['id']);
  $all_brands = find_all('brands');
  if(!$product){
    $session->msg("d","Missing product id.");
    redirect('product.php');
  }
?>
<?php
$product = find_by_id('products',(int)$_GET['id']);
$all_categories = find_all('categories');
$all_brand = find_all('brand');
$all_photo = find_all('media');
if(!$product){
  $session->msg("d","Missing product id.");
  redirect('product.php');
}
?>
<?php
 if(isset($_POST['product'])){
    $req_fields = array('product-title','product-categorie','product-quantity','product-color', 'product-brand' );
    validate_fields($req_fields);

   if(empty($errors)){
       $p_name  = remove_junk($db->escape($_POST['product-title']));
       $p_cat   = (int)$_POST['product-categorie'];
       $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
       $p_color = remove_junk($db->escape($_POST['product-color']));
       $p_brand = remove_junk($db->escape($_POST['product-brand']));
       if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
         $media_id = '0';
       } else {
         $media_id = remove_junk($db->escape($_POST['product-photo']));
       }
       $query   = "UPDATE products SET";
       $query  .= " name ='{$p_name}', quantity ='{$p_qty}',";
       $query  .= " color ='{$p_color}', brand_id ='{$p_brand}', categorie_id ='{$p_cat}', media_id='{$media_id}'";
       $query  .= " WHERE id ='{$product['id']}'";

       $result = $db->query($query);
       if($result && $db->affected_rows() === 1){
         $session->msg('s',"Producto ha sido actualizado. ");
         redirect('product.php', false);
       } else {
         $session->msg('d',' Lo siento, actualización falló.');
         redirect('edit_product.php?id='.$product['id'], false);
       }

   } else{
       $session->msg("d", $errors);
       redirect('edit_product.php?id='.$product['id'], false);
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
  <div class="panel panel-default">
    <div class="panel-heading">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>Edit item</span>
      </strong>
    </div>
    <div class="panel-body">
      <div class="col-md-7">
        <form method="post" action="edit_product.php?id=<?php echo (int)$product['id'] ?>">
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon">
                <i class="glyphicon glyphicon-th-large"></i>
              </span>
              <input type="text" class="form-control" name="product-title" value="<?php echo remove_junk($product['name']);?>">
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-6">
                <select class="form-control" name="product-categorie">
                  <option value="">Select Shelft</option>
                  <?php  foreach ($all_categories as $cat): ?>
                    <option value="<?php echo (int)$cat['id']; ?>" <?php if($product['categorie_id'] === $cat['id']): echo "selected"; endif; ?> >
                      <?php echo remove_junk($cat['name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <select class="form-control" name="product-photo">
                  <option value=""> Without image</option>
                  <?php  foreach ($all_photo as $photo): ?>
                    <option value="<?php echo (int)$photo['id'];?>" <?php if($product['media_id'] === $photo['id']): echo "selected"; endif; ?> >
                      <?php echo $photo['file_name'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-4">
                <label for="qty">Quantity</label>
                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="glyphicon glyphicon-shopping-cart"></i>
                  </span>
                  <input type="text" class="form-control" name="product-quantity" value="<?php echo remove_junk($product['quantity']);?>">
                </div>
              </div>
              <div class="col-md-4">
                <select class="form-control" name="product-brand">
                  <option value="">Selecet brand</option>
                  <?php  foreach ($all_brand as $brand): ?>
                    <option value="<?php echo (int)$brand['id']; ?>" <?php if($product['brand_id'] === $brand['id']): echo "selected"; endif; ?> >
                      <?php echo remove_junk($brand['name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-4">
                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-color" value="<?php echo remove_junk($product['color']);?>">
                </div>
              </div>
            </div>
          </div>
          <button type="submit" name="product" class="btn btn-danger">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>

