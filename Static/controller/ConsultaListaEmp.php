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

    $generoFiltro = isset($_GET['categoria']) ? $_GET['categoria'] : '';

    $sql = "SELECT noEmpleado, nombre, apellido, sexo, fechaNac, fechaIngreso, sueldo, cargo, telefono, direccion 
            FROM empleado WHERE noEmpleado NOT IN (1, 2)";
    
    if ($generoFiltro) {
        $sql .= " AND sexo = ?";
    }

    $stmt = $conn->prepare($sql);
    
    if ($generoFiltro) {
        $stmt->bind_param('s', $generoFiltro);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Crear el archivo de Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle("Empleados");

    // Insertar el logo en la esquina derecha
    $logo = new Drawing();
    $logo->setPath(__DIR__ . '/../img/logosinletras.png'); // Ruta de la imagen
    $logo->setCoordinates('K1');
    $logo->setOffsetX(10);
    $logo->setHeight(80);
    $logo->setWorksheet($sheet);

    // Título del reporte
    $sheet->setCellValue('A1', 'Lista de Empleados');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->mergeCells('A1:J1');
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Verificar el estado y asignar 'Todos' si está vacío
    if (empty($generoFiltro)) {
        $generoMostrado = 'Todos';
    } else {
        $generoMostrado = $generoFiltro;
    }

    // Información del género seleccionado
    $sheet->setCellValue('A2', 'Género: ' . $generoMostrado);
    $sheet->getStyle('A2')->getFont()->setItalic(true);

    // Encabezados de la hoja de Excel
    $encabezados = ['ID Empleado', 'Nombre', 'Apellido', 'Género', 'Fecha de Nacimiento', 'Fecha de Ingreso', 'Sueldo', 'Cargo', 'Teléfono', 'Dirección'];
    $col = 'A';
    foreach ($encabezados as $encabezado) {
        $sheet->setCellValue($col . '3', $encabezado);
        $sheet->getStyle($col . '3')->getFont()->setBold(true);
        $col++;
    }

    // Variables para las filas de datos
    $rowIndex = 4;

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sheet->setCellValue('A' . $rowIndex, $row['noEmpleado']);
            $sheet->setCellValue('B' . $rowIndex, $row['nombre']);
            $sheet->setCellValue('C' . $rowIndex, $row['apellido']);
            $sheet->setCellValue('D' . $rowIndex, $row['sexo']);
            $sheet->setCellValue('E' . $rowIndex, $row['fechaNac']);
            $sheet->setCellValue('F' . $rowIndex, $row['fechaIngreso']);
            $sheet->setCellValue('G' . $rowIndex, $row['sueldo']);
            $sheet->setCellValue('H' . $rowIndex, $row['cargo']);
            $sheet->setCellValue('I' . $rowIndex, $row['telefono']);
            $sheet->setCellValue('J' . $rowIndex, $row['direccion']);
            $rowIndex++;
        }
    } else {
        // Mensaje en caso de no encontrar empleados
        $sheet->setCellValue('A3', 'No se encontraron empleados.');
        $sheet->mergeCells('A3:J3');
        $sheet->getStyle('A3')->getFont()->setItalic(true);
    }

    // Ajuste de ancho automático para las columnas
    foreach (range('A', 'J') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Aplicar estilo de tabla
    $sheet->getStyle('A3:J' . ($rowIndex - 1))->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => Color::COLOR_BLACK],
            ],
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
                'argb' => 'D2CFD3', // Fondo verde claro para encabezados
            ],
        ],
    ]);
    $sheet->getStyle('A3:J3')->getFont()->setBold(true)->getColor()->setARGB(Color::COLOR_WHITE);
    $sheet->getStyle('A3:J3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('04531a'); // Encabezado en verde oscuro

    // Descargar el archivo como Excel
    $writer = new Xlsx($spreadsheet);
    $nombreArchivo = "listaEmpleados.xlsx";
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment; filename=\"$nombreArchivo\"");
    header("Cache-Control: max-age=0");

    $writer->save("php://output");
    exit;
?>
