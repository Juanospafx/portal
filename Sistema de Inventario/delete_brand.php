<?php
  require_once('includes/auth_check.php');
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>

<?php
  // Buscar la brand por ID
  $brand = find_by_id('brand', (int)$_GET['id']);
  if (!$brand) {
    $session->msg("d", "ID de brand vacío o no encontrado.");
    redirect('brand.php');
  }
?>

<?php
  // Eliminar la brand con el ID correcto
  $delete_id = delete_by_id('brand', (int)$brand['id']);
  if ($delete_id) {
      $session->msg("s", "Brand eliminada exitosamente.");
      redirect('brand.php');
  } else {
      $session->msg("d", "Error al eliminar la brand.");
      redirect('brand.php');
  }
?>
