<?php
    require __DIR__ . '/../../vendor/autoload.php';
    require __DIR__ . '/Connect/db.php'; // Ajusta la ruta
    include 'Sesion.php'; 

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    $generoFiltro = isset($_GET['categoria']) ? $_GET['categoria'] : '';

    $sql = "SELECT noEmpleado, nombre, apellido, sexo, fechaNac, fechaIngreso, sueldo, cargo, telefono, direccion FROM empleado where noEmpleado not in(1,2)";

    if ($generoFiltro) {
        $sql .= " WHERE sexo = ?";
    }

    $stmt = $conn->prepare($sql);

    // Si se agregó un filtro, se debe vincular el parámetro
    if ($generoFiltro) {
        $stmt->bind_param('s', $generoFiltro);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Crear el archivo de Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle("Empleados");

    // Agregar encabezados a la hoja
    $encabezados = ['ID Empleado', 'Nombre', 'Apellido', 'Género', 'Fecha de Nacimiento', 'Fecha de Ingreso', 'Sueldo', 'Cargo', 'Teléfono', 'Dirección'];
    $col = 'A';
    foreach ($encabezados as $encabezado) {
        $sheet->setCellValue($col . '1', $encabezado);
        $col++;
    }

    // Agregar los datos de los empleados
    $fila = 2;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sheet->setCellValue('A' . $fila, $row['noEmpleado']);
            $sheet->setCellValue('B' . $fila, $row['nombre']);
            $sheet->setCellValue('C' . $fila, $row['apellido']);
            $sheet->setCellValue('D' . $fila, $row['sexo']);
            $sheet->setCellValue('E' . $fila, $row['fechaNac']);
            $sheet->setCellValue('F' . $fila, $row['fechaIngreso']);
            $sheet->setCellValue('G' . $fila, $row['sueldo']);
            $sheet->setCellValue('H' . $fila, $row['cargo']);
            $sheet->setCellValue('I' . $fila, $row['telefono']);
            $sheet->setCellValue('J' . $fila, $row['direccion']);
            $fila++;
        }
    } else {
        echo "No hay empleados para exportar.";
        exit;
    }

    // Descargar el archivo como Excel
    $writer = new Xlsx($spreadsheet);
    $nombreArchivo = "listaEmpleados.xlsx";
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment; filename=\"$nombreArchivo\"");
    header("Cache-Control: max-age=0");

    $writer->save("php://output");
    exit;
?>
