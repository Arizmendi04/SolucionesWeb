<?php
    require __DIR__ . '/../../vendor/autoload.php'; // Ajusta la ruta
    require __DIR__ . '/Connect/db.php'; // Ajusta la ruta de conexión
    include 'Sesion.php';

    // Obtener el rango de fechas desde los parámetros GET
    $fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : '';
    $fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : '';

    // Ajustar la consulta SQL con el filtro de fechas
    $sql = "SELECT 
                p.folio, 
                p.nombreProd, 
                SUM(v.cantidad) AS cantidad_vendida, 
                SUM(v.total) AS ingresos_generados
            FROM 
                venta v
            JOIN 
                producto p ON v.folio = p.folio
            JOIN 
                notaVenta nv ON v.idNotaVenta = nv.idNotaVenta
            WHERE 
                nv.fecha BETWEEN ? AND ?
            GROUP BY 
                p.folio, p.nombreProd
            ORDER BY 
                cantidad_vendida DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $fechaInicio, $fechaFin);
    $stmt->execute();
    $result = $stmt->get_result();

    // Crear el objeto TCPDF
    $pdf = new TCPDF();
    $pdf->SetTitle('Reporte de Productos Más Vendidos');
    $pdf->SetHeaderData('', 0, 'Reporte de Productos Más Vendidos', "Fecha: " . date('d/m/Y'));

    // Configuración de fuente
    $pdf->SetFont('helvetica', '', 8);

    // Agregar una página
    $pdf->AddPage();

    // Título
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 25, 'Productos Más Vendidos', 0, 1, 'C');
    // Agregar la imagen del logo en la parte superior derecha con tamaño ajustado
    $logoPath = __DIR__ . '/../img/logosinletras.png'; // Ruta de la imagen del logo
    $pdf->Image($logoPath, 170, 10, 25, 25, '', '', '', false, 200, '', false, false, 0, false, false, false);

    // Espacio para separación
    $pdf->Ln(7);

    // Tabla de productos vendidos con estilos
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->SetFillColor(0, 128, 0); // Color verde para los encabezados
    $pdf->Cell(40, 7, 'Código Producto', 1, 0, 'C', 1);
    $pdf->Cell(90, 7, 'Nombre Producto', 1, 0, 'C', 1);
    $pdf->Cell(30, 7, 'Cantidad Vendida', 1, 0, 'C', 1);
    $pdf->Cell(30, 7, 'Ingresos Generados', 1, 1, 'C', 1);

    // Establecer el estilo de celdas de contenido
    $pdf->SetFont('helvetica', '', 8);

    // Variable para acumular los ingresos generados
    $totalIngresos = 0;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $pdf->Cell(40, 7, $row['folio'], 1, 0, 'C');
            $pdf->Cell(90, 7, $row['nombreProd'], 1, 0, 'C');
            $pdf->Cell(30, 7, $row['cantidad_vendida'], 1, 0, 'C');
            $pdf->Cell(30, 7, '$' . number_format($row['ingresos_generados'], 2), 1, 1, 'C');

            // Acumular los ingresos generados
            $totalIngresos += $row['ingresos_generados'];
        }
    } else {
        $pdf->Cell(0, 7, 'No hay productos vendidos en este rango de fechas.', 0, 1, 'C');
    }

    // Mostrar el total de los ingresos generados con color azul

    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->SetTextColor(0, 0, 255); // Color azul para el total
    $pdf->Cell(190, 7, 'Total Ingresos: $' . number_format($totalIngresos, 2), 1, 1, 'C');

    // Descargar el archivo PDF
    $pdf->Output('reporteProductosMasVendidos.pdf', 'I');
    exit;
?>
