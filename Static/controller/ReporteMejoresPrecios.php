<?php
    require __DIR__ . '/../../vendor/autoload.php'; // Ajusta la ruta
    require __DIR__ . '/Connect/db.php'; // Ajusta la ruta de conexión
    include 'Sesion.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
    use PhpOffice\PhpSpreadsheet\Style\Fill;
    use PhpOffice\PhpSpreadsheet\Style\Border;
    use PhpOffice\PhpSpreadsheet\Style\Color;

    // Obtener los valores del formulario
    $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
    $peso = isset($_GET['peso']) ? $_GET['peso'] : '';
    $unidad = isset($_GET['unidad']) ? $_GET['unidad'] : '';

    // Consulta SQL para obtener todos los productos de todos los proveedores en la categoría seleccionada
    $sql = "SELECT 
                p.nombreProd AS producto,
                p.precio AS precio,
                p.unidadM AS unidad,
                p.peso AS peso,
                pr.razonSocial AS proveedor
            FROM 
                producto p
            JOIN 
                proveedor pr ON p.idProveedor = pr.idProveedor
            WHERE 
                p.tipo = ? 
                AND p.unidadM = ?
            ORDER BY 
                pr.razonSocial, p.precio ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $categoria, $unidad);
    $stmt->execute();
    $result = $stmt->get_result();

    // Crear un nuevo documento de Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Reporte Comparativo de Precios');

    // Insertar el logo de la empresa en la esquina derecha (celda F1)
    $logo = new Drawing();
    $logo->setPath(__DIR__ . '/../img/logosinletras.png'); // Ruta de la imagen del logo
    $logo->setCoordinates('F1');
    $logo->setOffsetX(10);
    $logo->setHeight(80);
    $logo->setWorksheet($sheet);

    // Título del reporte
    $sheet->setCellValue('A1', 'Reporte Comparativo de Precios');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->mergeCells('A1:F1');
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Información de filtros seleccionados
    $sheet->setCellValue('A2', 'Categoría: ' . $categoria);
    $sheet->setCellValue('A3', 'Unidad de medida: ' . $unidad);
    $sheet->setCellValue('A4', 'Peso: ' . $peso);
    $sheet->getStyle('A2:A4')->getFont()->setItalic(true);

    // Encabezados de la hoja de Excel
    $sheet->setCellValue('A5', 'Producto');
    $sheet->setCellValue('B5', 'Proveedor');
    $sheet->setCellValue('C5', 'Precio');
    $sheet->setCellValue('D5', 'Unidad de Medida');
    $sheet->setCellValue('E5', 'Peso');
    $sheet->setCellValue('F5', 'Precio por ' . $unidad);

    // Formato de encabezado
    $sheet->getStyle('A5:F5')->getFont()->setBold(true);
    $sheet->getStyle('A5:F5')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('04531a'); // Color verde

    // Variables para las filas
    $rowIndex = 6;
    $currentProveedor = ''; // Variable para verificar el cambio de proveedor
    $proveedoresPrecios = []; // Arreglo para almacenar proveedores y precios

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Verificamos si el proveedor ha cambiado para insertar una fila vacía entre proveedores
            if ($currentProveedor != $row['proveedor']) {
                if ($currentProveedor != '') {
                    // Insertar una fila en blanco antes de empezar con el siguiente proveedor
                    $rowIndex++;
                }
                // Actualizamos el proveedor actual
                $currentProveedor = $row['proveedor'];
            }

            // Calculamos el precio por unidad (kg, litro, etc.)
            $precioPorUnidad = $row['precio'] / $row['peso']; 

            // Insertamos los datos del producto en la hoja de Excel
            $sheet->setCellValue('A' . $rowIndex, $row['producto']);
            $sheet->setCellValue('B' . $rowIndex, $row['proveedor']);
            $sheet->setCellValue('C' . $rowIndex, $row['precio']);
            $sheet->setCellValue('D' . $rowIndex, $row['unidad']);
            $sheet->setCellValue('E' . $rowIndex, $row['peso']);
            $sheet->setCellValue('F' . $rowIndex, $precioPorUnidad);

            // Almacenamos el proveedor y su precio para la comparación final
            $proveedoresPrecios[$row['proveedor']][] = $precioPorUnidad;
            $rowIndex++;
        }

        // Descripción final con la recomendación del proveedor más conveniente
        $sheet->setCellValue('A' . ($rowIndex + 2), 'Resumen:');
        $sheet->setCellValue('A' . ($rowIndex + 3), 'Con los datos seleccionados, el proveedor que ofrece el mejor precio por unidad es:');
        
        // Encontramos el proveedor con el precio más bajo
        $mejorProveedor = '';
        $mejorPrecio = PHP_INT_MAX;
        foreach ($proveedoresPrecios as $proveedor => $precios) {
            $precioMinimo = min($precios);
            if ($precioMinimo < $mejorPrecio) {
                $mejorPrecio = $precioMinimo;
                $mejorProveedor = $proveedor;
            }
        }

        $sheet->setCellValue('A' . ($rowIndex + 4), $mejorProveedor . ' con un precio por ' . $unidad . ' de ' . number_format($mejorPrecio, 2));

    } else {
        $sheet->setCellValue('A6', 'No se encontraron productos para la categoría, peso o unidad de medida especificados');
        $sheet->mergeCells('A6:F6');
    }

    // Ajuste de ancho automático para las columnas
    foreach (range('A', 'F') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Generar el archivo Excel para descarga
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ReporteComparativoPrecios.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
?>
