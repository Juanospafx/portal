<!-- Main Content -->
<div class="row">
    <div class="col-md-12">
        <!-- Campo de búsqueda para filtrado en el cliente -->
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-md-12">
                <input id="searchInput" type="text" class="form-control" placeholder="Filtrar por nombre, código, categoría, etc.">
            </div>
        </div>

        <h2>Publications</h2>
        <a href="index.php?view=newproduct" class="btn btn-default">Add Product</a>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-th-list"></i> Publicaciones
            </div>
            <div class="widget-body medium no-padding">
                <?php
                $categories = PostData::getAll();
                if (count($categories) > 0) : ?>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="productsTable">
                            <thead>
                                <tr>
                                    <th>Part code</th>
                                    <th>Name</th>
                                    <th>Categoty</th>
                                    <th>Visible</th>
                                    <th>Outstanding</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $cat) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($cat->code, ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($cat->name, ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($cat->category_id, ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td style="width:90px;">
                                            <center><?php echo $cat->is_public ? '<i class="fa fa-check"></i>' : '<i class="fa fa-remove"></i>'; ?></center>
                                        </td>
                                        <td style="width:90px;">
                                            <center><?php echo $cat->is_featured ? '<i class="fa fa-check"></i>' : '<i class="fa fa-remove"></i>'; ?></center>
                                        </td>
                                        <td style="width:185px;">
                                            <a href="../index.php?view=post&product_id=<?php echo $cat->id; ?>" target="_blank" class="btn btn-default btn-xs"><i class="fa fa-link"></i></a>
                                            <a href="index.php?view=editproduct&product_id=<?php echo $cat->id; ?>" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a>
                                            <a href="index.php?action=delproduct&product_id=<?php echo $cat->id; ?>" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <div class="panel-body">
                        <p class="alert alert-warning">There are no posts, you can start by adding your posts list.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Script de filtrado en vivo -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $("#searchInput").on("keyup", function () {
            var value = $(this).val().toLowerCase().trim();
            $("#productsTable tbody tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
</script>


