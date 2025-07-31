<?php
session_start();
ob_start(); // Evitar salida previa de datos

// Incluir archivos necesarios utilizando __DIR__ para construir rutas absolutas
require_once __DIR__ . '/../../autoload.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// ✅ Verificar que el carrito no esté vacío
$cart_items = $_SESSION["cart"] ?? [];
if (empty($cart_items)) {
    die("El carrito está vacío.");
}

// ✅ Validar formato de exportación
$format = $_GET["format"] ?? "csv";
$allowed_formats = ["csv", "pdf", "excel"];
if (!in_array($format, $allowed_formats)) {
    die("Formato no válido.");
}

// ✅ Si no se ha enviado el nombre del proyecto, mostrar el formulario
if (!isset($_POST["project_name"])) {
    ?>
    <form method="POST">
        <label for="project_name">Nombre del Proyecto:</label>
        <input type="text" name="project_name" required>
        <br><br>
        <label for="additional_info">¿Faltó algo más? (opcional):</label>
        <textarea name="additional_info" rows="3" cols="30"></textarea>
        <br><br>
        <input type="hidden" name="format" value="<?php echo htmlspecialchars($format); ?>">
        <button type="submit">Confirmar</button>
    </form>
    <?php
    exit;
}

// ✅ Obtener el nombre del proyecto y sanitizarlo
$project_name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $_POST["project_name"]);

// ✅ Obtener información adicional (si se ingresó)
$additional_info = isset($_POST["additional_info"]) ? trim($_POST["additional_info"]) : '';

// ✅ Recopilar datos del carrito
// Primero se agregan los encabezados y luego cada item
$data = [["ID", "Código", "Nombre", "Cantidad", "Unidad"]];

foreach ($cart_items as $product_id => $item) {
    // Si $item es un arreglo, se extrae cantidad y unidad; si no, se usa un valor por defecto
    if (is_array($item)) {
        $quantity = $item["quantity"];
        $unit     = $item["unit"];
    } else {
        $quantity = $item;
        $unit     = "ue"; // Unidad por defecto
    }

    $product = PostData::getById($product_id);
    if ($product) {
        $data[] = [
            $product->id,
            $product->code,
            $product->name,
            $quantity,
            $unit
        ];
    }
}

// Después de listar los items, se agrega una fila en blanco y luego la información adicional
if (!empty($additional_info)) {
    $data[] = []; // Fila vacía para separar
    // La fila de información adicional ocupa las primeras dos columnas y deja el resto en blanco
    $data[] = ["Información Adicional:", $additional_info, "", "", ""];
}

// ------------------------------------------------
// Exportar según el formato solicitado
// ------------------------------------------------

// ✅ Exportar CSV (no admite estilos, solo se agregan las filas)
if ($format == "csv") {
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename={$project_name}.csv");
    $output = fopen("php://output", "w");
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

// ✅ Exportar PDF
if ($format == "pdf") {
    ob_end_clean(); // Limpiar cualquier salida antes de generar el PDF
    $pdf = new TCPDF();
    $pdf->SetCreator('Brigtronix');
    $pdf->SetAuthor("Brigtronix");
    $pdf->SetTitle("Carrito de Compras");
    $pdf->AddPage();
    $pdf->SetFont("helvetica", "", 12);

    foreach ($data as $row) {
        // Si la fila está vacía, se agrega un salto de línea
        if (empty($row)) {
            $pdf->Ln();
        } elseif (isset($row[0]) && $row[0] === "Información Adicional:") {
            // Establecer fondo amarillo para la fila de información adicional
            $pdf->SetFillColor(255, 255, 0);
            $pdf->Cell(0, 10, implode(" | ", $row), 0, 1, '', true);
            // Reiniciar el color de relleno (opcional)
            $pdf->SetFillColor(255, 255, 255);
        } else {
            $pdf->Cell(0, 10, implode(" | ", $row), 0, 1);
        }
    }

    $pdf->Output("{$project_name}.pdf", "D");
    exit;
}

// ✅ Exportar Excel
if ($format == "excel") {
    ob_end_clean(); // Limpiar cualquier salida antes de generar el archivo
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Escribir los datos en la hoja
    foreach ($data as $index => $row) {
        $sheet->fromArray($row, null, "A" . ($index + 1));
    }

    // Si se ingresó información adicional, aplicar estilo amarillo a esa fila
    if (!empty($additional_info)) {
        // La fila de información adicional es la última (considerando la fila vacía también)
        $lastRow = count($data);
        // Si agregamos la fila vacía, la información adicional está en la última fila
        $sheet->getStyle("A{$lastRow}:E{$lastRow}")
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FFFFFF00'); // Amarillo
    }

    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment; filename={$project_name}.xlsx");

    $writer = new Xlsx($spreadsheet);
    $writer->save("php://output");
    exit;
}
?>
