<?php
    require __DIR__ . '/../../vendor/autoload.php';
    require __DIR__ . '/Connect/db.php';
    include 'Sesion.php';

    // Recuperar los valores seleccionados en el formulario
    $categoria = $_GET['categoria'];
    $proveedor = $_GET['proveedor'];

    // Crear el objeto PDF
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetTextColor(0, 128, 0);


    // Título del documento
    $pdf->Cell(0, 10, 'Reporte de Productos Disponibles por Tipo y Proveedor', 0, 1, 'C');
    $pdf->Ln(5);
    
    // Reiniciar el color del texto para el resto del contenido
    $pdf->SetTextColor(0, 0, 0);


     // Encabezados de la tabla con el nuevo campo para el proveedor y estilo para fondo gris
     $tbl = '<table border="1" cellpadding="4">
     <tr style="background-color: #D3D3D3;">
         <th><strong>Producto</strong></th>
         <th><strong>Tipo</strong></th>
         <th><strong>Unidad de Medida</strong></th>
         <th><strong>Existencia</strong></th>
         <th><strong>Proveedor</strong></th>
     </tr>';

    $totalExistencias = 0; // Variable para acumular las existencias totales


    try {
        // Modificar la consulta SQL para incluir el nombre del proveedor
        $sql = "SELECT p.nombreProd, p.tipo AS tipo_producto, p.unidadM, p.existencia AS cantidad_disponible, pr.razonSocial
                FROM producto p
                JOIN proveedor pr ON p.idProveedor = pr.idProveedor
                WHERE p.tipo = ? AND pr.idProveedor = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $categoria, $proveedor); 
        $stmt->execute();

        // Obtener los resultados
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC); // Recuperar los resultados como un array asociativo

        if (empty($data)) {
            throw new Exception("No se encontraron productos para los criterios seleccionados.");
        }

        // Agregar los datos a la tabla, ahora con el nombre del proveedor
        foreach ($data as $row) {
            $tbl .= '<tr>
                        <td>' . htmlspecialchars($row['nombreProd']) . '</td>
                        <td>' . htmlspecialchars($row['tipo_producto']) . '</td>
                        <td>' . htmlspecialchars($row['unidadM']) . '</td>
                        <td>' . htmlspecialchars($row['cantidad_disponible']) . '</td>
                        <td>' . htmlspecialchars($row['razonSocial']) . '</td>
                    </tr>';

            // Acumular la cantidad de existencias
            $totalExistencias += $row['cantidad_disponible'];
        }
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }

    $tbl .= '<tr style="background-color: #04531a;">
            <td colspan="3"><strong><span style="color: #ffffff;">Total Existencias</span></strong></td>
            <td colspan="2"><strong><span style="color: #ffffff;">' . $totalExistencias . '</span></strong></td>
        </tr>';

    $tbl .= '</table>';
    $pdf->writeHTML($tbl, true, false, false, false, '');

    // Crear el gráfico de barras
    $barWidth = 20;
    $barSpacing = 10;
    $xPos = 50;
    $yPos = 140;
    $maxHeight = 50;
    $maxValue = max(array_column($data, 'cantidad_disponible')); // Obtener el valor máximo de existencia

    // Título del gráfico
    $pdf->SetXY(10, 70); // Usa una posición específica en el PDF
    $pdf->Cell(0, 30, 'Existencias de Productos por Tipo y Proveedor', 0, 1, 'C');

    // Etiquetas de los ejes
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetXY(15, 110); // Posición de la etiqueta del eje Y
    $pdf->Cell(10, 10, 'Cantidad', 0, 0, 'C');
    $pdf->SetXY($xPos + (count($data) * ($barWidth + $barSpacing)) / 2, $yPos + 30); // Posición de la etiqueta del eje X
    $pdf->Cell(10, 10, 'Productos', 0, 0, 'C');
    $pdf->SetFont('helvetica', '', 10);

    // Dibujar las barras y las etiquetas de cantidad
    foreach ($data as $index => $row) {
        $barHeight = ($row['cantidad_disponible'] / $maxValue) * $maxHeight; // Escalar la altura de la barra
        $pdf->Rect($xPos + ($index * ($barWidth + $barSpacing)), $yPos - $barHeight, $barWidth, $barHeight, 'DF', [], [0, 128, 255]);

        // Rotar la etiqueta de la barra (45 grados)
        $pdf->StartTransform();
        $pdf->Rotate(45, $xPos + ($index * ($barWidth + $barSpacing)) + ($barWidth / 2), $yPos + 15);
        $pdf->SetXY($xPos + ($index * ($barWidth + $barSpacing)), $yPos + 15);
        $pdf->Cell($barWidth, 10, $row['nombreProd'], 0, 0, 'C');
        $pdf->StopTransform();

        // Etiqueta de cantidad en el eje Y (a la izquierda de las barras)
        $pdf->SetXY($xPos - 10, $yPos - $barHeight);
        $pdf->Cell(10, 10, $row['cantidad_disponible'], 0, 0, 'R');
    }


    // Salida del PDF
    $pdf->Output('ReporteProductosPorTipoYProveedor.pdf', 'I');
    exit;
?>