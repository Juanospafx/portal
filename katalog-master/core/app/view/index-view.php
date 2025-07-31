<?php 
$cnt = 0;
$slides = SlideData::getPublics();
$featureds = PostData::getFeatureds();
$img_default = "admin/storage/default.png"; // Imagen por defecto si no hay imagen disponible
?>
<section>
  <div class="container">
    <div class="row">
      <div class="col-md-12">

        <!-- 🚀 **Carrusel de Slides** -->
        <?php if (!empty($slides)): ?>
          <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <!-- Indicadores -->
            <ol class="carousel-indicators">
              <?php foreach ($slides as $index => $s): ?>
                <li data-target="#carousel-example-generic" data-slide-to="<?php echo $index; ?>" class="<?php echo ($index == 0) ? "active" : ""; ?>"></li>
              <?php endforeach; ?>
            </ol>

            <!-- Contenido del Carrusel -->
            <div class="carousel-inner">
              <?php foreach ($slides as $index => $s): 
                $url = "admin/storage/slides/" . htmlspecialchars($s->image);
              ?>
                <div class="item <?php echo ($index == 0) ? "active" : ""; ?>">
                  <img src="<?php echo $url; ?>" alt="Slide Image">
                  <div class="carousel-caption">
                    <h2><?php echo htmlspecialchars($s->title); ?></h2>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- Controles del Carrusel -->
            <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
              <span class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
              <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
          </div>
        <?php endif; ?>
        <br>

        <!-- 🚀 **Publicaciones Destacadas** -->
        <?php if (!empty($featureds)): ?>
          <a href="./">
            <div style="background:#333; font-size:25px; color:white; padding:5px;">Publicaciones Destacadas</div>
          </a>
          <br>

          <div class="row">
            <?php 
            $count = 0;
            foreach ($featureds as $p): 
              // ✅ Verificar si $p es un objeto
              if (!is_object($p)) continue; // Si no es un objeto, saltamos

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
            <h2>No hay publicaciones destacadas.</h2>
            <p>En la página principal solo se muestran publicaciones marcadas como destacadas.</p>
          </div>
        <?php endif; ?>
        
      </div>
    </div>
  </div>
</section>
