<?php
    require __DIR__ . '/../../vendor/autoload.php';
    require __DIR__ . '/Connect/db.php'; // Ajusta la ruta
    include 'Sesion.php'; 

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    // Consulta para obtener los productos
    $sql = "SELECT folio, nombreProd, tipo, unidadM, existencia, peso, descripcion, precio, urlImagen, idProveedor FROM producto";
    $result = $conn->query($sql);

    // Crear el archivo de Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle("Productos");

    // Agregar encabezados a la hoja
    $encabezados = ['Folio', 'Nombre', 'Tipo', 'Unidad de Medida', 'Existencia', 'Peso', 'Descripción', 'Precio', 'URL Imagen', 'ID Proveedor'];
    $col = 'A';
    foreach ($encabezados as $encabezado) {
        $sheet->setCellValue($col . '1', $encabezado);
        $col++;
    }

    // Agregar los datos de los productos
    $fila = 2;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sheet->setCellValue('A' . $fila, $row['folio']);
            $sheet->setCellValue('B' . $fila, $row['nombreProd']);
            $sheet->setCellValue('C' . $fila, $row['tipo']);
            $sheet->setCellValue('D' . $fila, $row['unidadM']);
            $sheet->setCellValue('E' . $fila, $row['existencia']);
            $sheet->setCellValue('F' . $fila, $row['peso']);
            $sheet->setCellValue('G' . $fila, $row['descripcion']);
            $sheet->setCellValue('H' . $fila, $row['precio']);
            $sheet->setCellValue('I' . $fila, $row['urlImagen']);
            $sheet->setCellValue('J' . $fila, $row['idProveedor']);
            $fila++;
        }
    } else {
        echo "No hay productos para exportar.";
        exit;
    }

    // Descargar el archivo como Excel
    $writer = new Xlsx($spreadsheet);
    $nombreArchivo = "listaProductos.xlsx";
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment; filename=\"$nombreArchivo\"");
    header("Cache-Control: max-age=0");

    $writer->save("php://output");
    exit;
?>
