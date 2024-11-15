<?php 
    require __DIR__ . '/../../vendor/autoload.php';
    require __DIR__ . '/Connect/db.php';
    include 'Sesion.php'; 

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
    use PhpOffice\PhpSpreadsheet\Style\Fill;
    use PhpOffice\PhpSpreadsheet\Style\Border;
    use PhpOffice\PhpSpreadsheet\Style\Color;

    // Obtener la categoría seleccionada del formulario
    $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

    // Consulta para obtener los productos de la categoría seleccionada
    if ($categoria) {
        $sql = "SELECT folio, nombreProd, tipo, unidadM, existencia, peso, descripcion, precio, urlImagen, idProveedor FROM producto WHERE tipo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $categoria);
    } else {
        $sql = "SELECT folio, nombreProd, tipo, unidadM, existencia, peso, descripcion, precio, urlImagen, idProveedor FROM producto";
        $stmt = $conn->prepare($sql);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Crear el archivo de Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle("Productos");

    // Insertar el logo de la empresa en la esquina derecha (celda K1)
    $logo = new Drawing();
    $logo->setPath(__DIR__ . '/../img/logosinletras.png'); // Ruta de la imagen del logo
    $logo->setCoordinates('K1');
    $logo->setOffsetX(10);
    $logo->setHeight(80);
    $logo->setWorksheet($sheet);

    // Título del reporte
    $sheet->setCellValue('A1', 'Lista de Productos');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->mergeCells('A1:J1');
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Información de la categoría seleccionada
    $sheet->setCellValue('A2', 'Categoría: ' . $categoria);
    $sheet->getStyle('A2')->getFont()->setItalic(true);

    // Encabezados de la hoja
    $encabezados = ['Folio', 'Nombre', 'Tipo', 'Unidad de Medida', 'Existencia', 'Peso', 'Descripción', 'Precio', 'URL Imagen', 'ID Proveedor'];
    $col = 'A';
    foreach ($encabezados as $encabezado) {
        $sheet->setCellValue($col . '3', $encabezado);
        $sheet->getStyle($col . '3')->getFont()->setBold(true);
        $col++;
    }

    // Variables para las filas
    $fila = 4;

    // Verificar si hay productos
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
        $sheet->setCellValue('A4', 'No se encontraron productos para la categoría seleccionada.');
        $sheet->mergeCells('A4:J4');
        $sheet->getStyle('A4')->getFont()->setItalic(true);
    }

    // Ajuste de ancho automático para las columnas
    foreach (range('A', 'J') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Aplicar estilo de tabla con color de fondo y bordes
    $sheet->getStyle('A3:J' . ($fila - 1))->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => Color::COLOR_BLACK],
            ],
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
                'argb' => 'D2CFD3', // Color de fondo verde para encabezados
            ],
        ],
    ]);
    $sheet->getStyle('A3:J3')->getFont()->setBold(true)->getColor()->setARGB(Color::COLOR_WHITE);
    $sheet->getStyle('A3:J3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('04531a'); // Encabezado en verde oscuro

    // Descargar el archivo como Excel
    $writer = new Xlsx($spreadsheet);
    $nombreArchivo = "listaProductos.xlsx";
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment; filename=\"$nombreArchivo\"");
    header("Cache-Control: max-age=0");

    $writer->save("php://output");
    exit;
?>
