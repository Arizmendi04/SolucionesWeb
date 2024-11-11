<?php include 'HeaderA.php'; ?>
<?php include '../../Controller/Connect/Db.php'; ?>
<?php include '../../Controller/Sesion.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Css/tickets.css">
</head>
<body>
    <div class="container">
        <h1>Gestión de Ventas</h1>

        <div class="layout">
            <!-- Tabla para consultar ventas -->
            <div class="tabla">
                <div class="busqueda">
                    <h2 align="center">Tickets</h2>
                    <input type="text" id="busqueda" placeholder="Buscar por cliente o producto" oninput="filtrarVentas(this.value)">
                </div>
                <table id="tablaVentas" class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Subtotal</th>
                            <th>IVA</th>
                            <th>Total</th>
                            <th>Estatus</th>
                            <th>Cliente</th>
                            <th>Empleado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $queryVentas = "SELECT v.idNotaVenta, v.fecha, v.subtotal, v.iva, v.PagoTotal, v.estatus, c.nombreC AS cliente, 
                        concat(e.nombre, ' ', e.apellido) AS empleado
                        FROM notaventa v
                        JOIN cliente c ON v.noCliente = c.noCliente
                        JOIN empleado e ON v.noEmpleado = e.noEmpleado
                        ORDER BY v.idNotaVenta ASC";
                        $resultVentas = $conn->query($queryVentas);

                        if ($resultVentas && $resultVentas->num_rows > 0) {
                            while ($venta = $resultVentas->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$venta['idNotaVenta']}</td>
                                    <td>{$venta['fecha']}</td>
                                    <td>$ {$venta['subtotal']}</td>
                                    <td>$ {$venta['iva']}</td>
                                    <td>$ {$venta['PagoTotal']}</td>
                                    <td>{$venta['estatus']}</td>
                                    <td>{$venta['cliente']}</td>
                                    <td>{$venta['empleado']}</td>
                                    <td><button class='btn btn-info' onclick='mostrarDetalles({$venta['idNotaVenta']})'>Mostrar</button></td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No hay tickets registrados</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Botón para generar ticket -->
            <button class="btn-generar-ticket btn btn-success" onclick="generarTicket()" >Generar Ticket</button>

            <!-- Detalle del ticket -->
            <div id="detalleTicket" class="detalle-ticket mt-5">
                <h3>Detalles del Ticket</h3>
                <ul id="listaProductos" class="list-group"></ul>
            </div>
        </div>
    </div>

    <script>
    function mostrarDetalles(ticketId) {
        const detalleTicket = document.getElementById('detalleTicket');
        detalleTicket.classList.add('mostrar');  // Mostrar el contenedor al hacer clic
        fetch(`../../Controller/ControladorTickets.php?accion=mostrar&id=${ticketId}`)
            .then(response => response.json())
            .then(data => {
                const lista = document.getElementById('listaProductos');
                lista.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(item => {
                        const elemento = document.createElement('li');
                        elemento.classList.add('list-group-item');
                        elemento.innerHTML = `
                            <strong>Producto:</strong> ${item.producto} |
                            <strong>Proveedor:</strong> ${item.proveedor} |
                            <strong>Precio:</strong> $${item.precio} |
                            <strong>Cantidad:</strong> ${item.cantidad} |
                            <strong>Subtotal:</strong> $${item.subtotal}
                        `;
                        lista.appendChild(elemento);
                    });
                } else {
                    lista.innerHTML = '<li class="list-group-item">No hay productos en este ticket</li>';
                }
            })
            .catch(error => console.error('Error al obtener los detalles:', error));
    }

    function generarTicket() {
        window.location.href = '/SolucionesWeb/Static/View/Admin/ViewGestionVent.php';
    }
    </script>
</body>
</html>
