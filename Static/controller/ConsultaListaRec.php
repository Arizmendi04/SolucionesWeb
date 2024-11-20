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

    // Obtenemos el proveedor seleccionado del formulario
    $idProveedor = isset($_GET['proveedor']) ? $_GET['proveedor'] : '';

    // Consulta para obtener las recepciones basadas en el proveedor seleccionado
    if ($idProveedor) {
        $sql = "SELECT 
                    r.idRep AS idRecepcion,
                    r.cantidadProducto,
                    r.fecha,
                    r.comentario,
                    p.nombreProd AS producto,
                    pr.razonSocial AS proveedor
                FROM 
                    recepcion r
                JOIN 
                    producto p ON r.folio = p.folio
                JOIN 
                    proveedor pr ON r.idProveedor = pr.idProveedor
                WHERE 
                    r.idProveedor = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idProveedor);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        echo "Proveedor no seleccionado.";
        exit;
    }

    // Creamos el archivo de Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle("Recepciones del Proveedor");

    // Insertamos el logo de la empresa en la esquina derecha (celda F1)
    $logo = new Drawing();
    $logo->setPath(__DIR__ . '/../img/logosinletras.png'); // Ruta de la imagen del logo
    $logo->setCoordinates('F1');
    $logo->setOffsetX(10);
    $logo->setHeight(80);
    $logo->setWorksheet($sheet);

    // Título del reporte
    $sheet->setCellValue('A1', 'Lista de Recepciones');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->mergeCells('A1:E1');
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Información del proveedor seleccionado
    $sheet->setCellValue('A2', 'Proveedor: ' . $idProveedor);
    $sheet->getStyle('A2')->getFont()->setItalic(true);

    // Encabezados de la hoja de Excel
    $encabezados = ['ID Recepción', 'Cantidad de Producto', 'Fecha', 'Comentario', 'Producto', 'Proveedor'];
    $col = 'A';
    foreach ($encabezados as $encabezado) {
        $sheet->setCellValue($col . '5', $encabezado);
        $sheet->getStyle($col . '5')->getFont()->setBold(true);
        $col++;
    }

    // Variables para las filas
    $rowIndex = 6;

    // Verificamos si hay resultados
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sheet->setCellValue('A' . $rowIndex, $row['idRecepcion']);
            $sheet->setCellValue('B' . $rowIndex, $row['cantidadProducto']);
            $sheet->setCellValue('C' . $rowIndex, $row['fecha']);
            $sheet->setCellValue('D' . $rowIndex, $row['comentario']);
            $sheet->setCellValue('E' . $rowIndex, $row['producto']);
            $sheet->setCellValue('F' . $rowIndex, $row['proveedor']);
            $rowIndex++;
        }
    } else {
        // Mensaje en caso de no encontrar recepciones para el proveedor seleccionado
        $sheet->setCellValue('A5', 'No se encontraron recepciones para el proveedor seleccionado.');
        $sheet->mergeCells('A5:F5');
        $sheet->getStyle('A5')->getFont()->setItalic(true);
    }

    // Ajuste de ancho automático para las columnas
    foreach (range('A', 'F') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Aplicamos estilo de tabla con color de fondo y bordes
    $sheet->getStyle('A5:F' . ($rowIndex - 1))->applyFromArray([
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
    $sheet->getStyle('A5:F5')->getFont()->setBold(true)->getColor()->setARGB(Color::COLOR_WHITE);
    $sheet->getStyle('A5:F5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('04531a'); // Encabezado en verde oscuro

    // Descargar el archivo como Excel
    $writer = new Xlsx($spreadsheet);
    $nombreArchivo = "listaRecepciones_Proveedor.xlsx";
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment; filename=\"$nombreArchivo\"");
    header("Cache-Control: max-age=0");

    $writer->save("php://output");
    exit;
?>
