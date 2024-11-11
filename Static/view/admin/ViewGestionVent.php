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
    <link rel="stylesheet" href="../../Css/ventas.css">
</head>
<body>
    <div class="container">
        <h1>Gestión de Ventas</h1>

        <!-- Obtener el último idNotaVenta -->
        <?php
            $queryUltimaNota = "SELECT idNotaVenta, estatus FROM notaVenta ORDER BY idNotaVenta DESC LIMIT 1";
            $resultUltimaNota = $conn->query($queryUltimaNota);
            $idNotaVenta = 0;

            if ($resultUltimaNota && $resultUltimaNota->num_rows > 0) {
                $ultimaNota = $resultUltimaNota->fetch_assoc();
                if ($ultimaNota['estatus'] === 'Pendiente') {
                    $idNotaVenta = $ultimaNota['idNotaVenta'];
                } else {
                    // Si no hay una nota de venta pendiente, crear una nueva
                    $queryNuevaNota = "INSERT INTO notaVenta (fecha, subtotal, iva, pagoTotal, estatus, noCliente, noEmpleado) 
                                       VALUES (CURDATE(), 0, 0, 0, 'Pendiente', NULL, NULL)";
                    if ($conn->query($queryNuevaNota) === TRUE) {
                        $idNotaVenta = $conn->insert_id; // Obtener el ID de la nueva nota
                    }
                }
            }
        ?>

        <!-- Select Cliente -->
        <label for="noCliente">Cliente:</label>
        <select id="noCliente" name="noCliente" required onchange="actualizarCampos();">
            <?php
                $queryClientes = "SELECT noCliente, nombreC FROM cliente";
                $resultClientes = $conn->query($queryClientes);

                if ($resultClientes && $resultClientes->num_rows > 0) {
                    while ($cliente = $resultClientes->fetch_assoc()) {
                        echo "<option value='{$cliente['noCliente']}'>{$cliente['nombreC']}</option>";
                    }
                } else {
                    echo "<option value=''>No hay clientes disponibles</option>";
                }
            ?>
        </select>

        <!-- Select Empleado -->
        <label for="noEmpleado">Empleado:</label>
        <select id="noEmpleado" name="noEmpleado" required onchange="actualizarCampos();">
            <?php
                $queryEmpleados = "SELECT noEmpleado, nombre, apellido FROM empleado";
                $resultEmpleados = $conn->query($queryEmpleados);

                if ($resultEmpleados && $resultEmpleados->num_rows > 0) {
                    while ($empleado = $resultEmpleados->fetch_assoc()) {
                        echo "<option value='{$empleado['noEmpleado']}'>{$empleado['nombre']} {$empleado['apellido']}</option>";
                    }
                } else {
                    echo "<option value=''>No hay empleados disponibles</option>";
                }
            ?>
        </select>
        
        <br><br>

        <div class="tabla">
            <div class="busqueda">
                <h2 align="center">Ticket</h2>
                <input type="text" id="busqueda" placeholder="Buscar por cliente o producto" oninput="filtrarVentas(this.value)">
                <label for="idNotaVenta">ID Ticket: <?php echo $idNotaVenta; ?></label><br><br>
            </div>
            <table id="tablaVentas">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($idNotaVenta > 0) {
                        $queryVentas = "SELECT v.idVenta, p.nombreProd AS productoNombre, v.cantidad, p.precio, (v.cantidad * p.precio) AS total
                                        FROM venta v
                                        JOIN producto p ON v.folio = p.folio
                                        WHERE v.idNotaVenta = $idNotaVenta";
                        $resultVentas = $conn->query($queryVentas);

                        if ($resultVentas && $resultVentas->num_rows > 0) {
                            while ($venta = $resultVentas->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$venta['idVenta']}</td>
                                    <td>{$venta['productoNombre']}</td>
                                    <td>{$venta['cantidad']}</td>
                                    <td>$ {$venta['precio']}</td>
                                    <td>$ {$venta['total']}</td>
                                    <td>
                                        <a href='modificarVenta.php?accion=editar&id={$venta['idVenta']}' class='btn btn-primary'>Editar</a>
                                        <a href='../../Controller/Ventas.php?accion=eliminar&id={$venta['idVenta']}' class='btn btn-danger' onclick='return confirm(\"¿Estás seguro de eliminar esta venta?\")'>Eliminar</a>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No hay productos en la venta actual</td></tr>";
                            echo "<script>document.getElementById('btnFinalizar').disabled = true;</script>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <br><br>
        
        <!-- Botones de acción -->
        <div class="botones-container">
            <!-- Consultar tickets -->
            <div align="center">
                <a href="/SolucionesWeb/Static/View/Admin/ViewGestionTickets.php">
                    <button class="btn btn-fin">Consultar tickets</button>
                </a>
            </div>
            
            <!-- Finalizar compra con lógica de validación y actualización -->
            <div align="center">
                <form method="POST" action="../../Controller/Tickets.php" onsubmit="return validateFinalizar();">
                    <input type="hidden" name="idNotaVenta" value="<?php echo $idNotaVenta; ?>"> <!-- Se pasa el idNotaVenta -->
                    <input type="hidden" name="noCliente" id="noClienteHidden">
                    <input type="hidden" name="noEmpleado" id="noEmpleadoHidden">
                    <button type="submit" class="btn btn-fin" name="finalizarCompra" id="btnFinalizar">Finalizar compra</button>
                </form>
            </div>
        </div>
        
    </div>

    <!-- Modal de advertencia -->
    <div class="modal fade" id="modalAdvertencia" tabindex="-1" aria-labelledby="modalAdvertenciaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAdvertenciaLabel">¡Advertencia!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <strong>No puedes finalizar la compra</strong> porque no hay productos en el ticket.
                    <br><br>
                    Por favor, agrega productos antes de continuar con la compra.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Actualizar los campos ocultos con los valores seleccionados
        function actualizarCampos() {
            const cliente = document.getElementById('noCliente').value;
            const empleado = document.getElementById('noEmpleado').value;

            document.getElementById('noClienteHidden').value = cliente;
            document.getElementById('noEmpleadoHidden').value = empleado;
        }

        // Validación para asegurarse de que el cliente y empleado estén seleccionados y haya productos
        function validateFinalizar() {
            const cliente = document.getElementById('noClienteHidden').value;
            const empleado = document.getElementById('noEmpleadoHidden').value;
            // Comprobar si el cliente y el empleado están seleccionados
            if (!cliente || !empleado) {
                const modal = new bootstrap.Modal(document.getElementById('modalAdvertencia'));
                document.querySelector('#modalAdvertencia .modal-body').innerHTML = "Por favor, selecciona un cliente y un empleado.";
                modal.show();
                return false;
            }
            // Verificar si hay productos en la venta
            const rows = document.getElementById('tablaVentas').getElementsByTagName('tbody')[0].rows;
            let tieneProductos = false;
            // Iterar solo sobre las filas del cuerpo (excluyendo encabezados)
            for (let i = 0; i < rows.length; i++) {
                // Asegurarnos de que la fila contiene productos y no el mensaje de "No hay productos en la venta actual"
                if (rows[i].cells[1] && rows[i].cells[1].innerText !== "No hay productos en la venta actual") {
                    tieneProductos = true;
                    break; // Salir del bucle si encontramos un producto válido
                }
            }
            // Si no hay productos, mostrar el modal y evitar el envío del formulario
            if (!tieneProductos) {
                const modal = new bootstrap.Modal(document.getElementById('modalAdvertencia'));
                modal.show();  // Abrir el modal
                return false;  // Evitar el envío del formulario
            }
            return true; // Permitir la finalización si hay productos
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../Controller/Js/ConfirmElim.js"></script>
    <script src="../../Controller/Js/Validaciones.js"></script>
</body>
</html>
