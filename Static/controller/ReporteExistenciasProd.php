<?php
    require __DIR__ . '/../../vendor/autoload.php';
    require __DIR__ . '/Connect/db.php'; 

    // Recuperar los valores seleccionados en el formulario
    $categoria = $_GET['categoria'];
    $proveedor = $_GET['proveedor'];

    // Crear el objeto PDF
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);
    
    // Agregar la imagen del logo en la parte superior derecha con tamaño ajustado
    $logoPath = __DIR__ . '/../img/logosinletras.png'; // Ruta de la imagen del logo
    $pdf->Image($logoPath, 170, 10, 25, 25, '', '', '', false, 300, '', false, false, 0, false, false, false);

    // Establecer el color verde para el título y hacerlo en negrita
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->SetTextColor(0, 128, 0);
    $pdf->Cell(0, 15, 'Reporte de Productos Disponibles por Tipo y Proveedor', 0, 1, 'C');
    $pdf->Ln(5);


    $pdf->Ln(15);

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
            // Mostrar mensaje de "No se encontraron productos" y asignar total en 0
            $tbl .= '<tr>
                        <td colspan="5" style="text-align: center;"><strong>No se encontraron productos para los criterios seleccionados.</strong></td>
                    </tr>';
            $totalExistencias = 0;
        } else {
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
        }
    } catch (Exception $e) {
        $tbl .= '<tr>
                    <td colspan="5" style="text-align: center;"><strong>Error: ' . htmlspecialchars($e->getMessage()) . '</strong></td>
                </tr>';
        $totalExistencias = 0;
    }

    $tbl .= '<tr style="background-color: #04531a;">
            <td colspan="3"><strong><span style="color: #ffffff;">Total Existencias</span></strong></td>
            <td colspan="2"><strong><span style="color: #ffffff;">' . $totalExistencias . '</span></strong></td>
        </tr>';

    $tbl .= '</table>';
    $pdf->writeHTML($tbl, true, false, false, false, '');

    // Salida del PDF
    $pdf->Output('ReporteProductosPorTipoYProveedor.pdf', 'I');
    exit;
?>
