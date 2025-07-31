<?php
$product = SlideData::getById($_GET["slide_id"]);
$url = "storage/slides/$product->image";
?>
        <!-- Main Content -->

          <div class="row">
            <div class="col-md-12">
  <!-- Button trigger modal -->
            <h2><?php echo $product->title;  ?> <small>Edit</small></h2>
            <?php
            // print_r($_SESSION);
             if(isset($_SESSION["product_updated"])):?>
              <p class="alert alert-info"><i class="fa fa-check"></i> Slide Successfully Updated</p>
            <?php 
            unset($_SESSION["product_updated"]);
            endif; ?>
            </div>
            </div>
          <div class="row">
            <div class="col-md-12">
              <div class="widget">
                <div class="widget-title">
                  <i class="fa fa-pencil"></i> Edit Slide
                </div>
                <div class="widget-body ">
<form class="form-horizontal" role="form" enctype="multipart/form-data" method="post" action="index.php?action=updateslide">
  <div class="form-group">
   

    <label for="inputEmail1" class="col-lg-2 control-label">Tittle</label>
    <div class="col-lg-10">
      <input type="text" class="form-control" name="title" value="<?php echo $product->title; ?>" placeholder="Tittle">
    </div>
    
  </div>
<img src="<?php echo $url; ?>" class="img-responsive">

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Image</label>
    <div class="col-lg-10">
      <input type="file" name="image">
    </div>
  </div>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-3">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="is_public" <?php if($product->is_public){ echo "checked";} ?> > It is Visible
        </label>
      </div>
    </div>

  </div>



  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-6">
      <button type="submit" class="btn btn-success btn-block">Edit Slide</button>
    </div>
    <div class="col-lg-4">
      <button type="reset" class="btn btn-default btn-block">Clear Fields</button>
    </div>
  </div>
  <input type="hidden" name="id" value="<?php echo $_GET["slide_id"];?>">
</form>

                  
                </div>
              </div>
            </div>

          </div>

<br><br>