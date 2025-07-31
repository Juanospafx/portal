
<div class="content">
<div class="row">

        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo count(PostData::getAll()); ?></h3>

              <p>Publications</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="./?view=posts" class="small-box-footer">See <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo count(CategoryData::getAll()); ?></h3>

              <p>Categories</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="./?view=categories" class="small-box-footer">See <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>

      </div>
