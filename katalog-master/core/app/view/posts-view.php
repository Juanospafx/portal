<?php 
// Inicializar productos como un array vacío por seguridad
$products = [];

// Verificar si se está filtrando por categoría
if (isset($_GET["cat"]) && !empty($_GET["cat"])) {
    $catx = CategoryData::getByPreffix($_GET["cat"]);
    if ($catx) {
        $products = PostData::getPublicsByCategoryId($catx->id);
    }
} 
// Filtrar por opciones especiales (nuevos, ofertas)
else if (isset($_GET["opt"])) {
    if ($_GET["opt"] == "news") {
        $products = PostData::getNews();
    } else if ($_GET["opt"] == "offers") {
        $products = PostData::getOffers();
    }
}
// Buscar productos
else if (isset($_GET["act"]) && $_GET["act"] == "search") {
    $products = PostData::getLike($_GET["q"]);
}
// Obtener todos los productos si no hay filtro
else {
    $products = PostData::getAll();
}

$img_default = "admin/storage/default.png"; // Imagen por defecto
?>

<section>
  <div class="container">
    <div class="row">
      <!-- 🚀 **Sidebar de Categorías** -->
      <div class="col-md-3">
        <h1>Categorías</h1>
        <?php $cats = CategoryData::getPublics(); ?>
        <?php if (!empty($cats)): ?>
          <div class="list-group">
            <?php foreach ($cats as $cat): ?>
              <a href="index.php?view=posts&cat=<?php echo htmlspecialchars($cat->short_name); ?>" class="list-group-item">
                <i class="fa fa-chevron-right"></i> <?php echo htmlspecialchars($cat->name); ?>
              </a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- 🚀 **Contenido Principal** -->
      <div class="col-md-9">
        <h2 class="entry-title">
          <span>
            <?php 
            if (isset($_GET["act"]) && $_GET["act"] == "search") {
                echo "Búsqueda: " . htmlspecialchars($_GET["q"]); 
            } else if (isset($_GET["cat"]) && isset($catx)) { 
                echo htmlspecialchars($catx->name); 
            } else { 
                echo "Publicaciones"; 
            }
            ?>
          </span>
        </h2>
        <br>

        <?php if (!empty($products)): ?>
          <div class="row">
            <?php 
            $count = 0;
            foreach ($products as $p): 
              $img = (!empty($p->image)) ? "admin/storage/products/" . htmlspecialchars($p->image) : $img_default;
            ?>
              <div class="col-md-4 d-flex flex-column align-items-center text-center" style="margin-bottom: 20px; min-height: 250px;">
                <img src="<?php echo $img; ?>" style="width:120px; height:120px; object-fit: contain;">
                <h4 style="min-height: 50px;"><?php echo htmlspecialchars($p->name); ?></h4>
                <a href="index.php?view=post&product_id=<?php echo $p->id; ?>" class="btn btn-info mt-auto">Detalles</a>
              </div>

              <!-- Insertar clearfix cada 3 productos para evitar desajustes -->
              <?php if (($count + 1) % 3 == 0): ?>
                <div class="clearfix"></div>
              <?php endif; ?>

              <?php $count++; ?>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="jumbotron text-center">
            <h2>No hay Publicaciones</h2>
            <p>No hay publicaciones disponibles en este momento.</p>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</section>
<br><br>
