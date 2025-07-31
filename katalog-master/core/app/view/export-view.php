<?php
if (!isset($_GET["format"])) {
    die("Error: No se especificó el formato de exportación.");
}

$format = $_GET["format"];
$allowed_formats = ["csv", "pdf", "excel"];

if (!in_array($format, $allowed_formats)) {
    die("Error: Formato no válido.");
}

// Redirigir a export.php (o export-action.php) con el mismo formato
header("Location: ../action/export.php?format=$format");
exit;
?>

