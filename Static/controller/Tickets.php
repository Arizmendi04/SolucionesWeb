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
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['accion'] == 'crear') {
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

    // Filtrar tickets
    if (isset($_GET['accion']) && $_GET['accion'] == 'filtrar') {
        $parametro = isset($_GET['parametro']) ? $_GET['parametro'] : '';
        $parametro = $conn->real_escape_string($parametro);

        $resultado = filtrarTickets($conn, $parametro);

        if ($resultado && count($resultado) > 0) {
            foreach ($resultado as $ticket) {
                echo "<div class='list-group-item' onclick='seleccionarTicket(\"{$ticket['idTicket']}\", \"{$ticket['fecha']}\")'>Ticket #{$ticket['idTicket']} - {$ticket['fecha']}</div>";
            }
        } else {
            echo "<div>No se encontraron tickets.</div>";
        }
    }

?>
