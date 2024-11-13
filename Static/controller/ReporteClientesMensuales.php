<?php
    require __DIR__ . '/../../vendor/autoload.php'; // Ajusta la ruta
    require __DIR__ . '/Connect/db.php'; // Ajusta la ruta de conexión
    include 'Sesion.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    // Agregar un título al reporte en la primera fila
    $sheet->setCellValue('A1', 'Reporte de Compras por Cliente');
    $sheet->mergeCells('A1:D1'); // Combina las celdas A1 a D1 para centrar el título
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14); // Establecer el tamaño de fuente y negrita
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Centrar título

    // Agregar un subtítulo con el mes y el estado seleccionados
    $sheet->setCellValue('A2', "Mes: $mes, Estado: $estado");
    $sheet->mergeCells('A2:D2'); // Combina las celdas A2 a D2 para centrar el subtítulo
    $sheet->getStyle('A2')->getFont()->setItalic(true); // Poner en cursiva
    $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Centrar subtítulo

    // Encabezados de la hoja de Excel
    $sheet->setCellValue('A3', 'ID Cliente');
    $sheet->setCellValue('B3', 'Nombre Cliente');
    $sheet->setCellValue('C3', 'Total Compras');
    $sheet->setCellValue('D3', 'Total Gastado');

    // Formato de encabezado
    $sheet->getStyle('A3:D3')->getFont()->setBold(true);
    $sheet->getStyle('A3:D3')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FF00FF00'); // Color verde

    // Variables para filas y acumulador de ingresos
    $rowIndex = 4;
    $totalGastado = 0;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sheet->setCellValue('A' . $rowIndex, $row['id_cliente']);
            $sheet->setCellValue('B' . $rowIndex, $row['nombre_cliente']);
            $sheet->setCellValue('C' . $rowIndex, $row['total_compras']);
            $sheet->setCellValue('D' . $rowIndex, $row['total_gastado']);
            
            $totalGastado += $row['total_gastado'];
            $rowIndex++;
        }
        
        // Total gastado al final de la tabla
        $sheet->setCellValue('C' . $rowIndex, 'Total Gastado:');
        $sheet->setCellValue('D' . $rowIndex, $totalGastado);
        $sheet->getStyle('C' . $rowIndex . ':D' . $rowIndex)->getFont()->setBold(true);
        $sheet->getStyle('D' . $rowIndex)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE); // Color azul
    } else {
        $sheet->setCellValue('A4', 'No hay compras registradas en este mes y estado.');
        $sheet->mergeCells('A4:D4');
    }

    // Ajuste de ancho automático para las columnas
    foreach (range('A', 'D') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Generar el archivo Excel para descarga
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ReporteComprasPorCliente.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
?>
