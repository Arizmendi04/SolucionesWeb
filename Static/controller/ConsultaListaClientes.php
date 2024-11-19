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

    // Obtener el valor del filtro de estado, si existe
    $estadoFiltro = isset($_GET['estado']) ? $_GET['estado'] : '';

    // Ajustar la consulta SQL con el filtro
    $sql = "SELECT noCliente, clienteRFC, nombreC, razonSocial, email, telefonoC, calle, colonia, localidad, municipio, estado, clienteCP FROM cliente";

    // Si se seleccionó un estado, agregar el filtro a la consulta
    if ($estadoFiltro) {
        $sql .= " WHERE estado = ?";
    }

    $stmt = $conn->prepare($sql);

    // Si se agregó un filtro, se debe vincular el parámetro
    if ($estadoFiltro) {
        $stmt->bind_param('s', $estadoFiltro);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Crear el archivo de Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle("Clientes");

    // Insertar el logo de la empresa en la esquina derecha (celda M1)
    $logo = new Drawing();
    $logo->setPath(__DIR__ . '/../img/logosinletras.png'); // Ruta de la imagen del logo
    $logo->setCoordinates('M1');
    $logo->setOffsetX(10);
    $logo->setHeight(80);
    $logo->setWorksheet($sheet);

    // Título del reporte
    $sheet->setCellValue('A1', 'Lista de Clientes');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->mergeCells('A1:L1');
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Verificar el estado y asignar 'Todos' si está vacío
    if (empty($estadoFiltro)) {
        $estadoMostrado = 'Todos';
    } else {
        $estadoMostrado = $estadoFiltro;
    }

    // Información del estado seleccionado
    $sheet->setCellValue('A2', 'Estado: ' . $estadoMostrado);
    $sheet->getStyle('A2')->getFont()->setItalic(true);

    // Encabezados de la hoja de Excel
    $encabezados = ['ID Cliente', 'RFC', 'Nombre', 'Razón Social', 'Email', 'Teléfono', 'Calle', 'Colonia', 'Localidad', 'Municipio', 'Estado', 'Código Postal'];
    $col = 'A';
    foreach ($encabezados as $encabezado) {
        $sheet->setCellValue($col . '5', $encabezado);
        $sheet->getStyle($col . '5')->getFont()->setBold(true);
        $col++;
    }

    // Variables para las filas
    $fila = 6;

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sheet->setCellValue('A' . $fila, $row['noCliente']);
            $sheet->setCellValue('B' . $fila, $row['clienteRFC']);
            $sheet->setCellValue('C' . $fila, $row['nombreC']);
            $sheet->setCellValue('D' . $fila, $row['razonSocial']);
            $sheet->setCellValue('E' . $fila, $row['email']);
            $sheet->setCellValue('F' . $fila, $row['telefonoC']);
            $sheet->setCellValue('G' . $fila, $row['calle']);
            $sheet->setCellValue('H' . $fila, $row['colonia']);
            $sheet->setCellValue('I' . $fila, $row['localidad']);
            $sheet->setCellValue('J' . $fila, $row['municipio']);
            $sheet->setCellValue('K' . $fila, $row['estado']);
            $sheet->setCellValue('L' . $fila, $row['clienteCP']);
            $fila++;
        }
    } else {
        // Mensaje en caso de no encontrar clientes para el estado seleccionado
        $sheet->setCellValue('A5', 'No se encontraron clientes para el estado seleccionado.');
        $sheet->mergeCells('A5:L5');
        $sheet->getStyle('A5')->getFont()->setItalic(true);
    }

    // Ajuste de ancho automático para las columnas
    foreach (range('A', 'L') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Aplicar estilo de tabla con color de fondo y bordes
    $sheet->getStyle('A5:L' . ($fila - 1))->applyFromArray([
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
    $sheet->getStyle('A5:L5')->getFont()->setBold(true)->getColor()->setARGB(Color::COLOR_WHITE);
    $sheet->getStyle('A5:L5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('04531a'); // Encabezado en verde oscuro

    // Descargar el archivo como Excel
    $writer = new Xlsx($spreadsheet);
    $nombreArchivo = "listaClientes.xlsx";
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment; filename=\"$nombreArchivo\"");
    header("Cache-Control: max-age=0");

    $writer->save("php://output");
    exit;
?>
