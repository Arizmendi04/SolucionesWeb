<?php include 'Connect/Db.php'; ?>
<?php include 'Sesion.php'; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/SolucionesWeb/Static/Model/Ticket.php'); ?>

<?php
    function solicitarTickets($conn) {
        $Ticket = new Ticket($conn);
        return $Ticket->obtenerTickets();
    }

    function filtrarTickets($conn, $parametro) {
        $Ticket = new Ticket($conn);
        /*return $Ticket->filtrarTicket($parametro);*/ 
    }

    function obtenerTicketPorID($conn, $idTicket) {
        $Ticket = new Ticket($conn);
        return $Ticket->obtenerTicket($idTicket);
    }

    // Crear Ticket
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'crear') {
        // Crear instancia de Ticket y establecer propiedades
        $ticket = new Ticket($conn);
        $ticket->setFecha($_POST['fecha']);
        $ticket->setSubtotal($_POST['subtotal']);
        $ticket->setIva($_POST['iva']);
        $ticket->setPagoTotal($_POST['total']);
        $ticket->setNoCliente($_POST['idCliente']);
        $ticket->setNoEmpleado($_POST['idEmpleado']);

        try {
            $ticket->insertarTicket();
            header('Location: /SolucionesWeb/Static/View/Admin/ViewGestionTickets.php');
            exit;
        } catch (mysqli_sql_exception $e) {
            echo "Error al insertar el ticket: " . $e->getMessage();
        }
    }

    // Eliminar Ticket
    if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar') {
        $idTicket = $_GET['id'];
        $ticket = new Ticket($conn);
        
        $resultado = $ticket->eliminarTicket($idTicket);
        if ($resultado) {
            header('Location: /SolucionesWeb/Static/View/Admin/ViewGestionTickets.php');
            exit;
        } else {
            echo "Error al eliminar el ticket: " . $conn->error;
        }
    }

?>

<?php
    // Si se ha enviado el formulario, se actualiza el estatus del ticket
    if (isset($_POST['finalizarCompra'])) {
        // Obtener los valores seleccionados
        $idCliente = $_POST['noCliente'];
        $idEmpleado = $_POST['noEmpleado'];

        if (!empty($idCliente) && !empty($idEmpleado)) {
            // Actualizar el estatus de la nota de venta a 'Pagado' o 'Terminado'
            $queryActualizarEstatus = "UPDATE notaVenta SET estatus = 'Pagado', noCliente = ?, noEmpleado = ? WHERE estatus = 'Pendiente' ORDER BY idNotaVenta DESC LIMIT 1";
            $stmt = $conn->prepare($queryActualizarEstatus);
            $stmt->bind_param("ii", $idCliente, $idEmpleado);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                echo "<script>alert('Compra finalizada correctamente.');</script>";
                header('Location: /SolucionesWeb/Static/View/Admin/ViewGestionTickets.php');
                exit;  // Asegúrate de detener la ejecución después de redirigir
            } else {
                echo "<script>alert('Error al finalizar la compra.');</script>";
            }
        } else {
            echo "<script>alert('Por favor, selecciona un cliente y un empleado.');</script>";
        }
    }
?>
