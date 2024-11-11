<?php
    require __DIR__ . '/../../vendor/autoload.php';
    require __DIR__ . '/Connect/db.php'; // Ajusta la ruta
    include 'Sesion.php'; 

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    // Consulta para obtener los clientes
    $sql = "SELECT noCliente, clienteRFC, nombreC, razonSocial, email, telefonoC, calle, colonia, localidad, municipio, estado, clienteCP FROM cliente";
    $result = $conn->query($sql);

    // Crear el archivo de Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle("Clientes");

    // Agregar encabezados a la hoja
    $encabezados = ['ID Cliente', 'RFC', 'Nombre', 'Razón Social', 'Email', 'Teléfono', 'Calle', 'Colonia', 'Localidad', 'Municipio', 'Estado', 'Código Postal'];
    $col = 'A';
    foreach ($encabezados as $encabezado) {
        $sheet->setCellValue($col . '1', $encabezado);
        $col++;
    }

    // Agregar los datos de los clientes
    $fila = 2;
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
        echo "No hay clientes para exportar.";
        exit;
    }

    // Descargar el archivo como Excel
    $writer = new Xlsx($spreadsheet);
    $nombreArchivo = "listaClientes.xlsx";
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment; filename=\"$nombreArchivo\"");
    header("Cache-Control: max-age=0");

    $writer->save("php://output");
    exit;
?>
