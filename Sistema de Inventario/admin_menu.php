<!-- Botón toggle para móviles (visible solo en xs) -->
<button type="button" class="btn btn-default visible-xs" data-toggle="collapse" data-target="#sidebarMenu" style="margin:10px 0;">
  <i class="glyphicon glyphicon-menu-hamburger"></i> Menú
</button>

<!-- Contenedor colapsable del menú -->
<div id="sidebarMenu" class="collapse in">
  <ul class="nav nav-pills nav-stacked">
    <li>
      <a href="admin.php">
        <i class="glyphicon glyphicon-home"></i>
        <span>Control Panel</span>
      </a>
    </li>
    <li>
      <a href="#" class="submenu-toggle">
        <i class="glyphicon glyphicon-user"></i>
        <span>Accesses</span>
      </a>
      <ul class="nav nav-pills nav-stacked submenu">
        <li><a href="group.php">Manage groups</a></li>
        <li><a href="users.php">Manage users</a></li>
      </ul>
    </li>
    <li>
      <a href="categorie.php">
        <i class="glyphicon glyphicon-indent-left"></i>
        <span>Depot</span>
      </a>
    </li>
    <li>
      <a href="brand.php">
        <i class="glyphicon glyphicon-indent-left"></i>
        <span>Brand</span>
      </a>
    </li>
    <li>
      <a href="#" class="submenu-toggle">
        <i class="glyphicon glyphicon-th-large"></i>
        <span>Items</span>
      </a>
      <ul class="nav nav-pills nav-stacked submenu">
        <li><a href="product.php">Manage Items</a></li>
        <li><a href="add_product.php">Add Items</a></li>
      </ul>
    </li>
    <li>
      <a href="media.php">
        <i class="glyphicon glyphicon-picture"></i>
        <span>Media</span>
      </a>
    </li>
    <li>
      <a href="#" class="submenu-toggle">
        <i class="glyphicon glyphicon-th-list"></i>
        <span>Inputs/outputs</span>
      </a>
      <ul class="nav nav-pills nav-stacked submenu">
        <li><a href="sales.php">Manage Inputs/outputs</a></li>
        <li><a href="add_sale.php">Add Inputs/outputs</a></li>
      </ul>
    </li>
    <li>
      <a href="#" class="submenu-toggle">
        <i class="glyphicon glyphicon-signal"></i>
        <span>Repo.Inputs/outputs</span>
      </a>
      <ul class="nav nav-pills nav-stacked submenu">
        <li><a href="sales_report.php">Inputs/outputs by dates</a></li>
        <li><a href="monthly_sales.php">Inputs/outputs by month</a></li>
        <li><a href="daily_sales.php">Daily Inputs/outputs</a></li>
      </ul>
    </li>
  </ul>
</div>
