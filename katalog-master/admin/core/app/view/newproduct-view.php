<?php
// newproduct-view.php
?>
<!-- Main Content -->
<div class="row">
  <div class="col-md-12">
    <h2>NEW PUBLICATION</h2>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <i class="fa fa-star"></i> New publication
      </div>
      <div class="panel-body">
        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data" action="index.php?action=addproduct">
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Part code</label>
            <div class="col-lg-2">
              <input type="text" class="form-control" name="code" placeholder="Part Code">
            </div>
            <label for="inputEmail1" class="col-lg-2 control-label">Name</label>
            <div class="col-lg-6">
              <input type="text" class="form-control" name="name" placeholder="Product name">
            </div>
          </div>
          
          <div class="form-group">
            <label for="inputPassword1" class="col-lg-2 control-label">Description</label>
            <div class="col-lg-10">
              <textarea class="form-control" id="inputPassword1" placeholder="Description" rows="6" name="description"></textarea>
            </div>
          </div>
          
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Image</label>
            <div class="col-lg-10">
              <input type="file" name="image">
            </div>
          </div>
          
          <div class="form-group">
            <div class="col-lg-offset-2 col-lg-2">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="is_public"> It is Visible?
                </label>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="is_featured"> It is Outstanding?
                </label>
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Category</label>
            <div class="col-lg-10">
              <?php
              $categories = CategoryData::getAll();
              if(count($categories) > 0):
              ?>
              <select name="category_id" class="form-control" required>
                <option value="">-- SELECT CATEGORY --</option>
                <?php foreach($categories as $cat): ?>
                <option value="<?php echo $cat->id; ?>"><?php echo $cat->name; ?></option>
                <?php endforeach; ?>
              </select>
              <?php endif; ?>
            </div>
          </div>
          
          <!-- Nuevo campo opcional para Subcategory -->
          <div class="form-group">
            <label for="subcategory_id" class="col-lg-2 control-label">Subcategory (Optional)</label>
            <div class="col-lg-10">
              <?php
              $subcategories = SubcategoryData::getAll();
              if(count($subcategories) > 0):
              ?>
              <select name="subcategory_id" class="form-control">
                <option value="">-- NONE --</option>
                <?php foreach($subcategories as $subcat): ?>
                <option value="<?php echo $subcat->id; ?>"><?php echo $subcat->name; ?></option>
                <?php endforeach; ?>
              </select>
              <?php endif; ?>
            </div>
          </div>
          
          <div class="form-group">
            <div class="col-lg-offset-2 col-lg-6">
              <button type="submit" class="btn btn-primary btn-block">Agregar Publicacion</button>
            </div>
            <div class="col-lg-4">
              <button type="reset" class="btn btn-default btn-block">Limpiar Campos</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<br><br>
