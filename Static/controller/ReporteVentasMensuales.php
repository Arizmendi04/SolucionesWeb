<?php
    require __DIR__ . '/../../vendor/autoload.php'; // Ajusta la ruta
    require __DIR__ . '/Connect/db.php'; // Ajusta la ruta de conexión
    include 'Sesion.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    // Obtener el mes y el empleado desde los parámetros GET
    $mes = isset($_GET['mes']) ? $_GET['mes'] : '';
    $empleado = isset($_GET['empleado']) ? $_GET['empleado'] : '';

    // Consulta SQL para obtener el total de ventas mensuales agrupadas por empleado
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
            MONTH(nv.fecha) = ?
            AND e.noEmpleado = ? and e.noEmpleado not in(1,2)
        GROUP BY 
            e.noEmpleado
        ORDER BY 
            ingresos_generados DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $mes, $empleado);
    $stmt->execute();
    $result = $stmt->get_result();

    // Crear un nuevo documento de Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Reporte de Ventas Mensuales');

    // Encabezados de la hoja de Excel
    $sheet->setCellValue('A1', 'ID Empleado');
    $sheet->setCellValue('B1', 'Nombre Empleado');
    $sheet->setCellValue('C1', 'Total Ventas');
    $sheet->setCellValue('D1', 'Ingresos Generados');

    // Formato de encabezado
    $sheet->getStyle('A1:D1')->getFont()->setBold(true);
    $sheet->getStyle('A1:D1')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FF00FF00'); // Color verde

    // Variables para filas y acumulador de ingresos
    $rowIndex = 2;
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
        $sheet->getStyle('D' . $rowIndex)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE); // Color azul
    } else {
        $sheet->setCellValue('A2', 'No hay ventas realizadas en este mes por el empleado seleccionado');
        $sheet->mergeCells('A2:D2');
    }

    // Ajuste de ancho automático para las columnas
    foreach (range('A', 'D') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Generar el archivo Excel para descarga
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ReporteVentasMensuales.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
?>
