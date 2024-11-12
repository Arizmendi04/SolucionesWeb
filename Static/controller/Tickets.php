<?php
include 'Connect/Db.php';
include 'Sesion.php';
include($_SERVER['DOCUMENT_ROOT'].'/SolucionesWeb/Static/Model/Ticket.php');

// Obtener lista de tickets
function solicitarTickets($conn) {
    $Ticket = new Ticket($conn);
    return $Ticket->obtenerTickets();
}

// Obtener detalles de un ticket específico
function obtenerDetallesDeTicket($conn, $idNotaVenta) {
    $Ticket = new Ticket($conn);
    return $Ticket->obtenerDetallesTicket($idNotaVenta);
}

// Crear Ticket
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'crear') {
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

// Actualizar estatus del ticket
if (isset($_POST['finalizarCompra'])) {
    $idCliente = $_POST['noCliente'];
    $idEmpleado = $_POST['noEmpleado'];

    if (!empty($idCliente) && !empty($idEmpleado)) {
        $queryActualizarEstatus = "UPDATE notaVenta SET estatus = 'Pagado', noCliente = ?, noEmpleado = ? WHERE estatus = 'Pendiente' ORDER BY idNotaVenta DESC LIMIT 1";
        $stmt = $conn->prepare($queryActualizarEstatus);
        $stmt->bind_param("ii", $idCliente, $idEmpleado);

        if ($stmt->execute()) {
            echo "<script>alert('Compra finalizada correctamente.');</script>";
            header('Location: /SolucionesWeb/Static/View/Admin/ViewGestionTickets.php');
            exit;
        } else {
            echo "<script>alert('Error al finalizar la compra.');</script>";
        }
    } else {
        echo "<script>alert('Por favor, selecciona un cliente y un empleado.');</script>";
    }
}

// Mostrar detalles del ticket en formato JSON
if (isset($_GET['accion']) && $_GET['accion'] == 'mostrar' && isset($_GET['id'])) {
    $idNotaVenta = $_GET['id'];
    $ticket = new Ticket($conn);
    $detalles = $ticket->obtenerDetallesTicket($idNotaVenta);

    // Enviar la respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode($detalles);
    exit;
}
?>
