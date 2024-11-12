<?php
    require __DIR__ . '/../../vendor/autoload.php'; // Asegúrate de ajustar la ruta de TCPDF
    require __DIR__ . '/Connect/db.php'; // Ajusta la ruta para la conexión a la base de datos
    
    // Recuperar los valores seleccionados en el formulario
    $categoria = $_GET['categoria'];
    $proveedor = $_GET['proveedor'];
    
    // Crear el objeto PDF
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);
    
    // Establecer el color verde para el título
    $pdf->SetTextColor(0, 128, 0);
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
    
    $tbl .= '<tr>
                <td colspan="3"><strong>Total Existencias</strong></td>
                <td colspan="2"><strong>' . $totalExistencias . '</strong></td>
             </tr>';
    $tbl .= '</table>';
    $pdf->writeHTML($tbl, true, false, false, false, '');
    
    // Salida del PDF
    $pdf->Output('ReporteProductosPorTipoYProveedor.pdf', 'I');
    exit;
?>
