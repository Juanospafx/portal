<?php
// subcategory-view.php
?>
<!-- Main Content -->
<div class="row">
  <div class="col-md-12">
    <!-- Botón para abrir el modal de "Add Subcategory" -->
    <a data-toggle="modal" href="#myModal" class="pull-right btn-sm btn btn-default">Add Subcategory</a>
    <!-- Modal para Agregar Subcategoría -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Add Subcategory</h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal" role="form" method="post" action="index.php?action=addsubcategory">
              <div class="form-group">
                <label for="name" class="col-lg-2 control-label">Name</label>
                <div class="col-lg-10">
                  <input type="text" required class="form-control" name="name" placeholder="Subcategory Name">
                </div>
              </div>
              <div class="form-group">
                <label for="short_name" class="col-lg-2 control-label">Short Name</label>
                <div class="col-lg-10">
                  <input type="text" required class="form-control" name="short_name" placeholder="Short Name">
                </div>
              </div>
              <!-- Seleccionar Categoría principal -->
              <div class="form-group">
                <label for="category_id" class="col-lg-2 control-label">Category</label>
                <div class="col-lg-10">
                  <select name="category_id" class="form-control" required>
                    <option value="">Select Category</option>
                    <?php 
                    $categories = CategoryData::getPublics();
                    if(count($categories) > 0):
                      foreach($categories as $cat):
                    ?>
                      <option value="<?php echo $cat->id; ?>"><?php echo htmlspecialchars($cat->name); ?></option>
                    <?php 
                      endforeach;
                    endif;
                    ?>
                  </select>
                </div>
              </div>
              <!-- Checkbox con value="1" -->
              <div class="form-group">
                <div class="col-lg-offset-2 col-lg-10">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="is_active" value="1"> Active Subcategory
                    </label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-offset-2 col-lg-10">
                  <button type="submit" class="btn btn-block btn-primary">Add Subcategory</button>
                </div>
              </div>
            </form>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <h1>Subcategories</h1>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <i class="fa fa-th-list"></i> Subcategories
      </div>
      <div class="widget-body medium no-padding">
        <?php
        // Obtener todas las subcategorías usando SubcategoryData
        $subcategories = SubcategoryData::getAll();
        if(count($subcategories) > 0):
        ?>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <th></th>
              <th>Name</th>
              <th>Active</th>
              <th>Category</th>
              <th></th>
            </thead>
            <tbody>
              <?php foreach($subcategories as $subcat): ?>
              <tr>
                <td style="width:30px;">
                  <!-- Enlace para ver productos filtrados por esta subcategoría -->
                  <a href="index.php?view=posts&subcat=<?php echo $subcat->short_name; ?>" target="_blank" class="btn btn-default btn-xs">
                    <i class="fa fa-eye"></i>
                  </a>
                </td>
                <td><?php echo htmlspecialchars($subcat->name); ?></td>
                <td style="width:70px;">
                  <?php if($subcat->is_active): ?>
                    <center><i class="fa fa-check"></i></center>
                  <?php endif; ?>
                </td>
                <td>
                  <?php
                    // Obtener el nombre de la categoría principal
                    $cat = CategoryData::getById($subcat->category_id);
                    echo ($cat) ? htmlspecialchars($cat->name) : "N/A";
                  ?>
                </td>
                <td style="width:90px;">
                  <!-- Botón para editar subcategoría -->
                  <a data-toggle="modal" href="#myModal-<?php echo $subcat->id;?>" class="btn btn-warning btn-xs">
                    <i class="fa fa-edit"></i>
                  </a>
                  <!-- Modal para Editar Subcategoría -->
                  <div class="modal fade" id="myModal-<?php echo $subcat->id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-<?php echo $subcat->id;?>" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                          <h4 class="modal-title">Edit Subcategory</h4>
                        </div>
                        <div class="modal-body">
                          <form class="form-horizontal" role="form" method="post" action="index.php?action=updatesubcategory">
                            <div class="form-group">
                              <label for="name" class="col-lg-2 control-label">Name</label>
                              <div class="col-lg-10">
                                <input type="text" required class="form-control" name="name" value="<?php echo htmlspecialchars($subcat->name);?>" placeholder="Subcategory Name">
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="short_name" class="col-lg-2 control-label">Short Name</label>
                              <div class="col-lg-10">
                                <input type="text" required class="form-control" name="short_name" value="<?php echo htmlspecialchars($subcat->short_name);?>" placeholder="Short Name">
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="category_id" class="col-lg-2 control-label">Category</label>
                              <div class="col-lg-10">
                                <select name="category_id" class="form-control" required>
                                  <option value="">Select Category</option>
                                  <?php 
                                  $categories = CategoryData::getPublics();
                                  if(count($categories) > 0):
                                    foreach($categories as $cat):
                                  ?>
                                    <option value="<?php echo $cat->id; ?>" <?php if($subcat->category_id == $cat->id) echo "selected"; ?>>
                                      <?php echo htmlspecialchars($cat->name); ?>
                                    </option>
                                  <?php
                                    endforeach;
                                  endif;
                                  ?>
                                </select>
                              </div>
                            </div>
                            <!-- Checkbox con value="1" -->
                            <div class="form-group">
                              <div class="col-lg-offset-2 col-lg-10">
                                <div class="checkbox">
                                  <label>
                                    <input type="checkbox" name="is_active" value="1" <?php if($subcat->is_active) echo "checked";?>> Active Subcategory
                                  </label>
                                </div>
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-lg-offset-2 col-lg-10">
                                <input type="hidden" name="subcategory_id" value="<?php echo $subcat->id; ?>">
                                <button type="submit" class="btn btn-block btn-success">Update Subcategory</button>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                  </div><!-- /.modal -->
                  <!-- Botón para eliminar subcategoría -->
                  <a href="index.php?action=delsubcategory&subcategory_id=<?php echo $subcat->id; ?>" class="btn btn-danger btn-xs">
                    <i class="fa fa-trash"></i>
                  </a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
