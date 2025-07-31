<?php
$product = PostData::getById($_GET["product_id"]);
$url = "storage/products/$product->image";

// Obtener la subcategoría asignada (si existe)
$existingSubcat = ProductSubcategoriesData::getByProductId($product->id);
?>
<!-- Main Content -->
<div class="row">
    <div class="col-md-12">
        <!-- Button trigger modal -->
        <h2><?php echo $product->name;  ?> <small>Editar</small></h2>
        <?php
        if(isset($_SESSION["product_updated"])): ?>
            <p class="alert alert-info"><i class="fa fa-check"></i>Product Successfully Updated</p>
        <?php 
            unset($_SESSION["product_updated"]);
        endif; ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> Edit Product
            </div>
            <div class="panel-body ">
                <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post" action="index.php?action=updateproduct">
                    <div class="form-group">
                        <label for="inputEmail1" class="col-lg-2 control-label">Part Code</label>
                        <div class="col-lg-2">
                            <input type="text" class="form-control" name="code" value="<?php echo $product->code; ?>" placeholder="Part Code">
                        </div>

                        <label for="inputEmail1" class="col-lg-2 control-label">Name</label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Product name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword1" class="col-lg-2 control-label">Description</label>
                        <div class="col-lg-10">
                            <textarea class="form-control" id="inputPassword1" placeholder="Descripcion" rows="6" name="description"><?php echo $product->description; ?></textarea>
                        </div>
                    </div>

                    <?php if($product->image != "" && file_exists($url)): ?>
                        <img src="<?php echo $url; ?>" class="img-responsive">
                    <?php endif; ?>
                    <br>
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
                                    <input type="checkbox" name="is_public" <?php if($product->is_public){ echo "checked"; } ?>> It is Visible?
                                </label>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="is_featured" <?php if($product->is_featured){ echo "checked"; } ?>> It is Outstanding?
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail1" class="col-lg-2 control-label">Category</label>
                        <div class="col-lg-10">
                            <?php
                            $categories = CategoryData::getAll();
                            if(count($categories) > 0): ?>
                                <select name="category_id" class="form-control">
                                    <option value="">-- SELECT CATEGORY --</option>
                                    <?php foreach($categories as $cat): ?>
                                        <option value="<?php echo $cat->id; ?>" <?php if($product->category_id == $cat->id){ echo "selected"; } ?>><?php echo $cat->name; ?></option>
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
                            ?>
                            <select name="subcategory_id" class="form-control">
                                <option value="">-- NONE --</option>
                                <?php foreach($subcategories as $sub): ?>
                                    <option value="<?php echo $sub->id; ?>" <?php if($existingSubcat && $existingSubcat->id == $sub->id) echo "selected"; ?>><?php echo $sub->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-6">
                            <button type="submit" class="btn btn-success btn-block">Update Product</button>
                        </div>
                        <div class="col-lg-4">
                            <button type="reset" class="btn btn-default btn-block">Clean Fields</button>
                        </div>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $_GET["product_id"]; ?>">
                </form>
            </div>
        </div>
    </div>
</div>
<br><br>
