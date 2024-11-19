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

    // Obtener mes y empleado desde GET
    $mes = isset($_GET['mes']) ? $_GET['mes'] : '';
    $empleado = isset($_GET['empleado']) ? $_GET['empleado'] : '';

    // Consulta para obtener el nombre del empleado seleccionado
    $empleadoNombre = '';
    $empleadoSQL = "SELECT CONCAT(nombre, ' ', apellido) AS nombre_completo FROM empleado WHERE noEmpleado = ?";
    $empleadoStmt = $conn->prepare($empleadoSQL);
    $empleadoStmt->bind_param('s', $empleado);
    $empleadoStmt->execute();
    $empleadoResult = $empleadoStmt->get_result();

    if ($empleadoResult->num_rows > 0) {
        $empleadoRow = $empleadoResult->fetch_assoc();
        $empleadoNombre = $empleadoRow['nombre_completo'];
    }

    // Consulta principal para obtener las ventas
    $sql = "SELECT 
            e.noEmpleado AS id_empleado,
            e.nombre AS nombre_empleado,
            e.apellido AS apellido_empleado,
            COUNT(v.idVenta) AS total_ventas,
            SUM(v.total) AS ingresos_generados
        FROM 
            venta v
        JOIN 
            notaVenta nv ON v.idNotaVenta = nv.idNotaVenta
        JOIN 
            empleado e ON nv.noEmpleado = e.noEmpleado
        WHERE 
            MONTH(nv.fecha) = ? AND e.noEmpleado = ? AND e.noEmpleado NOT IN (1, 2)
        GROUP BY 
            e.noEmpleado
        ORDER BY 
            ingresos_generados DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $mes, $empleado);
    $stmt->execute();
    $result = $stmt->get_result();

    // Crear documento Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Reporte de Ventas Mensuales');

    // Logo
    $logo = new Drawing();
    $logo->setPath(__DIR__ . '/../img/logosinletras.png');
    $logo->setCoordinates('F1');
    $logo->setOffsetX(10);
    $logo->setHeight(80);
    $logo->setWorksheet($sheet);

    // Título
    $sheet->setCellValue('A1', 'Reporte de Ventas Mensuales');
    $sheet->mergeCells('A1:D1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);

    // Información seleccionada
    $sheet->setCellValue('A2', "Mes: $mes, Empleado: $empleadoNombre");
    $sheet->mergeCells('A2:D2');
    $sheet->getStyle('A2')->getFont()->setItalic(true);

    // Encabezados
    $encabezados = ['ID Empleado', 'Nombre Empleado', 'Total Ventas', 'Ingresos Generados'];
    $col = 'A';
    foreach ($encabezados as $encabezado) {
        $sheet->setCellValue($col . '5', $encabezado);
        $sheet->getStyle($col . '5')->getFont()->setBold(true) ->getColor()->setARGB(Color::COLOR_WHITE);
        $col++;
    }

    // Datos
    $rowIndex = 6;
    $totalIngresos = 0;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sheet->setCellValue('A' . $rowIndex, $row['id_empleado']);
            $sheet->setCellValue('B' . $rowIndex, $row['nombre_empleado'] . ' ' . $row['apellido_empleado']);
            $sheet->setCellValue('C' . $rowIndex, $row['total_ventas']);
            $sheet->setCellValue('D' . $rowIndex, $row['ingresos_generados']);
            $totalIngresos += $row['ingresos_generados'];
            $rowIndex++;
        }

        // Total de ingresos generados al final de la tabla
        $sheet->setCellValue('C' . $rowIndex, 'Total Ingresos:');
        $sheet->setCellValue('D' . $rowIndex, $totalIngresos);
        $sheet->getStyle('C' . $rowIndex . ':D' . $rowIndex)->getFont()->setBold(true);
        $sheet->getStyle('D' . $rowIndex)->getFont()->getColor()->setARGB(Color::COLOR_BLUE); // Color azul
    } else {
        $sheet->setCellValue('A6', 'No hay ventas realizadas en este mes por el empleado seleccionado');
        $sheet->mergeCells('A6:D6');
        $sheet->getStyle('A6')->getFont()->setItalic(true);
    }


    // Estilo y ajustes
    foreach (range('A', 'D') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Aplicar estilo de bordes y color a la tabla
    $sheet->getStyle('A5:D' . ($rowIndex - 1))->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => Color::COLOR_BLACK],
            ],
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
                'argb' => 'D2CFD3', // Color de fondo para datos
            ],
        ],
    ]);
    $sheet->getStyle('A5:D5')->getFont()->setBold(true)->getColor()->setARGB(Color::COLOR_WHITE);
    $sheet->getStyle('A5:D5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('04531a'); // Encabezado en verde oscuro



    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ReporteVentasMensuales.xlsx"');
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
?>
