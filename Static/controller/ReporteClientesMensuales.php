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

    // Obtener el mes y el estado desde los parámetros GET
    $mes = isset($_GET['mes']) ? $_GET['mes'] : '';
    $estado = isset($_GET['estado']) ? $_GET['estado'] : '';

    // Consulta SQL para obtener los clientes con más compras en un mes y estado específicos
    $sql = "SELECT 
                c.noCliente AS id_cliente,
                c.nombreC AS nombre_cliente,
                COUNT(nv.idNotaVenta) AS total_compras,
                SUM(nv.pagoTotal) AS total_gastado
            FROM 
                notaVenta nv
            JOIN 
                cliente c ON nv.noCliente = c.noCliente
            WHERE 
                MONTH(nv.fecha) = ? 
                AND (c.estado = ? OR ? = '')
            GROUP BY 
                c.noCliente
            ORDER BY 
                total_gastado DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $mes, $estado, $estado);
    $stmt->execute();
    $result = $stmt->get_result();

    // Crear un nuevo documento de Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Reporte de Compras por Cliente');

    // Insertar el logo de la empresa en la esquina derecha (celda F1)
    $logo = new Drawing();
    $logo->setPath(__DIR__ . '/../img/logosinletras.png'); // Ruta de la imagen del logo
    $logo->setCoordinates('F1');
    $logo->setOffsetX(10);
    $logo->setHeight(80);
    $logo->setWorksheet($sheet);

    // Agregar un título al reporte en la primera fila
    $sheet->setCellValue('A1', 'Reporte de Compras por Cliente');
    $sheet->mergeCells('A1:D1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Verificar el estado y asignar 'Todos' si está vacío
    if (empty($estado)) {
        $estadoMostrado = 'Todos';
    } else {
        $estadoMostrado = $estado;
    }
    // Agregar un subtítulo con el mes y el estado seleccionados
    $sheet->setCellValue('A2', "Mes: $mes, Estado: $estadoMostrado");
    $sheet->mergeCells('A2:D2');
    $sheet->getStyle('A2')->getFont()->setItalic(true);
    $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Encabezados de la hoja de Excel
    $encabezados = ['ID Cliente', 'Nombre Cliente', 'Total Compras', 'Total Gastado'];
    $col = 'A';
    foreach ($encabezados as $encabezado) {
        $sheet->setCellValue($col . '3', $encabezado);
        $sheet->getStyle($col . '3')->getFont()->setBold(true)->getColor()->setARGB(Color::COLOR_WHITE);
        $sheet->getStyle($col . '3')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('04531a'); // Color verde oscuro
        $col++;
    }

    // Variables para filas y acumulador de ingresos
    $rowIndex = 4;
    $totalGastado = 0;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sheet->setCellValue('A' . $rowIndex, $row['id_cliente']);
            $sheet->setCellValue('B' . $rowIndex, $row['nombre_cliente']);
            $sheet->setCellValue('C' . $rowIndex, $row['total_compras']);
            $sheet->setCellValue('D' . $rowIndex, $row['total_gastado']);
            
            // Aplicar color a la fila
            $sheet->getStyle('A' . $rowIndex . ':D' . $rowIndex)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('D2CFD3'); // Color de fondo gris claro

            $totalGastado += $row['total_gastado'];
            $rowIndex++;
        }
        
        // Total gastado al final de la tabla
        $sheet->setCellValue('C' . $rowIndex, 'Total Gastado:');
        $sheet->setCellValue('D' . $rowIndex, $totalGastado);
        $sheet->getStyle('C' . $rowIndex . ':D' . $rowIndex)->getFont()->setBold(true);
        $sheet->getStyle('D' . $rowIndex)->getFont()->getColor()->setARGB(Color::COLOR_BLUE); // Color azul
    } else {
        $sheet->setCellValue('A4', 'No hay compras registradas en este mes y estado.');
        $sheet->mergeCells('A4:D4');
        $sheet->getStyle('A4')->getFont()->setItalic(true);
    }

    // Ajuste de ancho automático para las columnas
    foreach (range('A', 'D') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Aplicar bordes a la tabla
    $sheet->getStyle('A3:D' . ($rowIndex - 1))->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => Color::COLOR_BLACK],
            ],
        ],
    ]);

    // Generar el archivo Excel para descarga
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ReporteComprasPorCliente.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
?>
