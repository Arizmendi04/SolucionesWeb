<?php 
    include 'Connect/Db.php'; 
    include 'Sesion.php'; 
?>

<?php
    // Eliminar venta
    if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar') {
        $id = $_GET['id'];
        $sql = "DELETE FROM venta WHERE idVenta = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        // Ejecutar la eliminación y comprobar si fue exitosa
        if ($stmt->execute()) {
            header('Location: /SolucionesWeb/Static/View/Admin/ViewGestionVent.php');
            exit; // Asegurarse de que el script se detenga después de redirigir
        } else {
            echo "Error al eliminar venta: " . $conn->error;
        }
    }
?>

<?php
    // Verificar si los datos se han recibido
    if (isset($_GET['idVenta'], $_GET['folioProducto'], $_GET['cantidad'], $_GET['precio'])) {
        $idVenta = $_GET['idVenta'];
        $folioProducto = $_GET['folioProducto'];
        $cantidad = (float)$_GET['cantidad'];  // Cambié canti a cantidad
        $precio = (float)$_GET['precio'];

        // Consultar si el producto ya existe en la venta
        $query = "SELECT * FROM venta WHERE idVenta = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idVenta);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Si el producto ya existe en la venta, lo actualizamos
            $total = $cantidad * $precio;

            // Actualizamos la venta
            $updateQuery = "UPDATE venta SET cantidad = ?, total = ?, folio = ? WHERE idVenta = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("diii", $cantidad, $total, $folioProducto, $idVenta);
            $updateStmt->execute();
            // Respuesta final
            echo 'Venta actualizada correctamente'; 
        } else {
            // Si el producto no está en la venta, lo insertamos
            $insertQuery = "INSERT INTO venta (idVenta, folio, cantidad, total) VALUES (?, ?, ?, ?)";
            $total = $cantidad * $precio;
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("iiid", $idVenta, $folioProducto, $cantidad, $total);
            $insertStmt->execute();
        }
    } else {
        // Si faltan parámetros, devolver un error
        echo 'Faltan datos';
    }
    // Redirigir después de mostrar los datos
    header('Location: /SolucionesWeb/Static/View/Admin/ViewGestionVent.php');
    exit;
?>
