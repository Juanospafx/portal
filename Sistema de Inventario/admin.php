<?php
  $page_title = 'Admin Home Page';
  require_once('auth_check.php');
  // Check what level user has permission to view this page
  page_require_level(1);
?>

<?php
 $c_categorie     = count_by_id('categories');
 $c_brand         = count_by_id('brand');
 $c_product       = count_by_id('products');
 $c_sale          = count_by_id('sales');
 $c_user          = count_by_id('users');
 $products_sold   = find_higest_saleing_product('10');
 $recent_products = find_recent_product_added('5');
 $recent_movements = find_recent_movements('5'); // Cambiado de recent_sales a recent_movements
?>

<?php include_once('layouts/header.php'); ?>


<div class="row">
   <div class="col-md-6">
     <?php echo display_msg($msg); ?>
   </div>
</div>

<!-- Tarjetas de estadísticas -->
<div class="row">
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-green">
          <i class="glyphicon glyphicon-user"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php echo $c_user['total']; ?> </h2>
          <p class="text-muted">Users</p>
        </div>
       </div>
    </div>

    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-green">
          <i class="fa fa-tags"></i>
        </div>
     <div class="panel-value pull-right">
       <h2 class="margin-top"> <?php echo $c_brand['total']; ?> </h2>
       <p class="text-muted">Brand</p>
     </div>
   </div>
</div>




    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-red">
          <i class="glyphicon glyphicon-home"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php echo $c_categorie['total']; ?> </h2>
          <p class="text-muted">Depot</p>
        </div>
       </div>
    </div>

    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-blue">
          <i class="glyphicon glyphicon-list-alt"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php echo $c_product['total']; ?> </h2>
          <p class="text-muted">Items</p>
        </div>
       </div>
    </div>

    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-yellow">
          <i class="glyphicon glyphicon-retweet"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php echo $c_sale['total']; ?></h2>
          <p class="text-muted">Inputs/outputs</p>
        </div>
       </div>
    </div>
</div>


<!-- Item más usado -->
<div class="row">
   <div class="col-md-4">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <span> Most used Items</span>
         </strong>
       </div>
       <div class="panel-body">
         <table class="table table-striped table-bordered table-condensed">
          <thead>
           <tr>
             <th>Title</th>
             <th>Total used</th>
             <th>Total quantity</th>
           </tr>
          </thead>
          <tbody>
            <?php foreach ($products_sold as  $product_sold): ?>
              <tr>
                <td><?php echo remove_junk(first_character($product_sold['name'])); ?></td>
                <td><?php echo (int)$product_sold['totalSold']; ?></td>
                <td><?php echo (int)$product_sold['totalQty']; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
         </table>
       </div>
     </div>
   </div>

   <!-- Última entrada/salida -->
   <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Last Inputs/outputs</span>
          </strong>
        </div>
        <div class="panel-body">
          <table class="table table-striped table-bordered table-condensed">
       <thead>
         <tr>
           <th>#</th>
           <th>Item</th>
           <th>Date</th>
           <th>Status</th>
         </tr>
       </thead>
       <tbody>
         <?php foreach ($recent_movements as  $movement): ?>
         <tr>
           <td><?php echo count_id(); ?></td>
           <td><?php echo remove_junk(first_character($movement['product_name'])); ?></td>
           <td><?php echo remove_junk(ucfirst($movement['date'])); ?></td>
           <td><?php echo remove_junk($movement['status']); ?></td>
        </tr>
       <?php endforeach; ?>
       </tbody>
     </table>
    </div>
   </div>
  </div>

  <!-- Items recientemente añadidos -->
  <div class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Items recently added</span>
        </strong>
      </div>
      <div class="panel-body">
        <div class="list-group">
      <?php foreach ($recent_products as  $recent_product): ?>
            <a class="list-group-item clearfix" href="edit_product.php?id=<?php echo (int)$recent_product['id'];?>">
                <div class="pull-left" style="margin-right: 10px;">
                 <?php if(empty($recent_product['image'])): ?>
                    <img class="img-avatar img-circle" src="uploads/products/no_image.jpg" alt="Sin imagen">
                  <?php else: ?>
                  <img class="img-avatar img-circle" src="uploads/products/<?php echo $recent_product['image'];?>" alt="" />
                <?php endif;?>
                </div>
                <h4 class="list-group-item-heading">
                <?php echo remove_junk(first_character($recent_product['name']));?>
              </h4>
              <span class="list-group-item-text pull-right">
                <?php echo remove_junk(first_character($recent_product['categorie'])); ?>
              </span>
          </a>
      <?php endforeach; ?>
    </div>
  </div>
 </div>
</div>

<?php include_once('layouts/footer.php'); ?>
