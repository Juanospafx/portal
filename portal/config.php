<?php
// config.php (v3 - Rutas de servidor correctas)
// Todas las apps aquí. Añade o modifica ítems fácilmente.
$apps = array(
    array(
        'name'       => 'clockin_app',
        'url'        => 'https://clockinapp.brightronix.net/clockin_app/public_html/frontend/index.html', // Asumiendo la misma estructura
        'desc'       => 'Registro de asistencia',
        'icon_class' => 'fas fa-clock'
    ),
    array(
        'name'       => 'Sistema de Inventario',
        'url'        => 'https://sistemadeinventario.brightronix.net/Sistema de Inventario/home.php',
        'desc'       => 'Control de stock',
        'icon_class' => 'fas fa-warehouse'
    ),
    array(
        'name'       => 'katalog-master',
        'url'        => 'https://catalogo.brightronix.net/katalog-master/index.php', // Asumiendo la misma estructura
        'desc'       => 'Catálogo de elementos',
        'icon_class' => 'fas fa-book-open'
    ),
);
?>