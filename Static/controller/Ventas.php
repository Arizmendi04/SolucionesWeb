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
