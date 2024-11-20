<?php

    include($_SERVER['DOCUMENT_ROOT'].'/SolucionesWeb/Static/Controller/Connect/Db.php');
    include($_SERVER['DOCUMENT_ROOT'].'/SolucionesWeb/Static/Controller/Sesion.php');
    include($_SERVER['DOCUMENT_ROOT'].'/SolucionesWeb/Static/Model/Ticket.php');

?>

<?php

    // Obtener lista de tickets
    function solicitarTickets($conn) {
        $Ticket = new Ticket($conn);
        return $Ticket->obtenerTickets();
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
            header('Location: /SolucionesWeb/Static/View/Empleado/ViewGestionTickets.php');
            exit;
        } catch (mysqli_sql_exception $e) {
            echo "Error al insertar el ticket: " . htmlspecialchars($e->getMessage());
        }
    }

    // Eliminar Ticket
    if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar') {
        $idTicket = $_GET['id'];
        $ticket = new Ticket($conn);

        $resultado = $ticket->eliminarTicket($idTicket);
        if ($resultado) {
            header('Location: /SolucionesWeb/Static/View/Empleado/ViewGestionTickets.php');
            exit;
        } else {
            echo "Error al eliminar el ticket: " . htmlspecialchars($conn->error);
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
                echo "<script>alert('Compra finalizada correctamente.');
                window.location.href = '/SolucionesWeb/Static/View/Empleado/ViewGestionTickets.php';</script>";
            } else {
                echo "<script>alert('Error al finalizar la compra.');
                window.location.href = '/SolucionesWeb/Static/View/Empleado/ViewGestionVent.php';
                </script>";
            }
        }
    }

    // Filtrar tickets por fecha
    if (isset($_GET['accion']) && $_GET['accion'] == 'filtrar') {
        $fecha = $_GET['fecha'] ?? ''; // Verifica si la fecha fue pasada
        $Ticket = new Ticket($conn);

        // Realizar la consulta de filtrado por fecha
        if ($fecha) {
            $query = "SELECT v.idNotaVenta, v.fecha, v.subtotal, v.iva, v.PagoTotal, v.estatus, c.nombreC AS cliente, CONCAT(e.nombre, ' ', e.apellido) AS empleado
                    FROM notaVenta v
                    JOIN cliente c ON v.noCliente = c.noCliente
                    JOIN empleado e ON v.noEmpleado = e.noEmpleado
                    WHERE v.fecha LIKE ? and v.noEmpleado != 2
                    ORDER BY v.idNotaVenta ASC";
            $stmt = $conn->prepare($query);
            $likeFecha = "$fecha%"; // Solo filtrar por fechas que comienzan con el valor ingresado
            $stmt->bind_param("s", $likeFecha);
        } else {
            // Si no se proporciona fecha, obtener todos los registros
            $query = "SELECT v.idNotaVenta, v.fecha, v.subtotal, v.iva, v.PagoTotal, v.estatus, 
                            c.nombreC AS cliente, CONCAT(e.nombre, ' ', e.apellido) AS empleado
                    FROM notaVenta v
                    JOIN cliente c ON v.noCliente = c.noCliente
                    JOIN empleado e ON v.noEmpleado = e.noEmpleado
                    WHERE v.noEmpleado != 2
                    ORDER BY v.idNotaVenta ASC";
            $stmt = $conn->prepare($query);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $resultados = [];
        if ($result->num_rows > 0) {
            // Si hay resultados, los recorremos para almacenarlos en un arreglo
            while ($venta = $result->fetch_assoc()) {
                $resultados[] = $venta; // Agregamos cada fila de resultados al arreglo
            }
        }
        $stmt->close();

        // Construcción segura de HTML
        $html = "";
        if (!empty($resultados)) {
            // Si hay resultados, generamos filas de tabla con los datos
            foreach ($resultados as $venta) {
                $html .= "<tr>
                            <td>" . htmlspecialchars($venta['idNotaVenta']) . "</td>
                            <td>" . htmlspecialchars($venta['fecha']) . "</td>
                            <td>$ " . htmlspecialchars($venta['subtotal']) . "</td>
                            <td>$ " . htmlspecialchars($venta['iva']) . "</td>
                            <td>$ " . htmlspecialchars($venta['PagoTotal']) . "</td>
                            <td>" . htmlspecialchars($venta['estatus']) . "</td>
                            <td>" . htmlspecialchars($venta['cliente']) . "</td>
                            <td>" . htmlspecialchars($venta['empleado']) . "</td>
                        </tr>";
                        //Utilizamos `htmlspecialchars` para prevenir inyección de HTML
            }
        } else {
            $html .= "<tr><td colspan='8'>No hay tickets que coincidan con la búsqueda</td></tr>";
        }
        
        echo $html;
        exit;
    }

?>
