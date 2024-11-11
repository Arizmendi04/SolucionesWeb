<?php
include '/SolucionesWeb/Static/Controller/Connect/Db.php';
include '/SolucionesWeb/Static/Controller/Productos.php';
include '/SolucionesWeb/Static/Controller/Sesion.php';

// Verificamos si se ha recibido el folio y la cantidad
if (isset($_GET['folio']) && isset($_GET['cantidad'])) {
    $folio = $_GET['folio'];  // Folio del producto
    $cantidad = $_GET['cantidad'];  // Cantidad del producto

    // Consultamos el precio y otros datos del producto
    $sql = "SELECT precio FROM producto WHERE folio = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $folio);
    $stmt->execute();
    $result = $stmt->get_result();
    $producto = $result->fetch_assoc();

    if ($producto) {
        // Paso 1: Verificamos el último idNotaVenta y su estatus
        $sqlNotaVenta = "SELECT idNotaVenta, estatus FROM notaVenta ORDER BY idNotaVenta DESC LIMIT 1";
        $notaVentaResult = $conn->query($sqlNotaVenta);
        $notaVenta = $notaVentaResult->fetch_assoc();

        if ($notaVenta && $notaVenta['estatus'] == 'Terminado') {
            // Si el estatus es "Terminado", creamos un nuevo idNotaVenta con el estatus "En compra"
            $sqlInsertNotaVenta = "INSERT INTO notaVenta (fecha, subtotal, iva, pagoTotal, estatus, noCliente, noEmpleado) 
                                   VALUES (CURDATE(), 0, 0, 0, 'En compra', ?, ?)";
            $stmtInsertNotaVenta = $conn->prepare($sqlInsertNotaVenta);
            $stmtInsertNotaVenta->bind_param("ii", $noCliente, $noEmpleado); // Ajusta los valores de noCliente y noEmpleado
            $stmtInsertNotaVenta->execute();

            // Obtenemos el nuevo idNotaVenta
            $idNotaVenta = $conn->insert_id;
        } else {
            // Si el estatus es "En compra", usamos el último idNotaVenta
            $idNotaVenta = $notaVenta['idNotaVenta'];
        }

        // Paso 2: Preparamos el query para insertar en la tabla de venta
        $insertQuery = "INSERT INTO venta (cantidad, total, folio, idNotaVenta) VALUES (?, ?, ?, ?)";
        
        // Calcular el total de la venta (precio * cantidad)
        $totalProducto = $producto['precio'] * $cantidad; 

        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("dii", $cantidad, $totalProducto, $folio, $idNotaVenta);
        
        if ($insertStmt->execute()) {
            // Si la inserción fue exitosa, retornamos un mensaje de éxito
            echo json_encode(['success' => true, 'message' => 'Producto agregado al carrito con éxito.']);
        } else {
            // Si hubo algún error en la inserción
            echo json_encode(['success' => false, 'message' => 'Error al agregar el producto al carrito.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Producto no encontrado.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Folio o cantidad no proporcionados.']);
}
?>
