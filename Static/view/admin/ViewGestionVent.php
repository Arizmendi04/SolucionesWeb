<?php include 'HeaderA.php'; ?>
<?php include '../../Controller/Connect/Db.php'; ?>
<?php include '../../Controller/Sesion.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
                <input type="text" id="busqueda" placeholder="Buscar por producto" oninput="filtrarVentas(this.value)">
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
                        $queryVentas = "SELECT v.idVenta, p.folio AS folioProd, p.nombreProd AS productoNombre, d.razonSocial as Razon, v.cantidad, p.precio, (v.cantidad * p.precio) AS total
                                        FROM venta v
                                        JOIN producto p ON v.folio = p.folio JOIN proveedor d on p.idProveedor = d.idProveedor
                                        WHERE v.idNotaVenta = $idNotaVenta";
                        $resultVentas = $conn->query($queryVentas);

                        if ($resultVentas && $resultVentas->num_rows > 0) {
                            while ($venta = $resultVentas->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$venta['idVenta']}</td>
                                    <td>{$venta['folioProd']} -> {$venta['productoNombre']} -> {$venta['Razon']}</td>
                                    <td>
                                        <input type='tel' class='entradaC boton advertencia' value='{$venta['cantidad']}' 
                                        onkeypress=\"if (event.key === 'Enter') { 
                                        enviarDatos(event, this, {$venta['folioProd']}, this.value, {$venta['precio']}); 
                                        }\">
                                    </td>
                                    <td>$ {$venta['precio']}</td>
                                    <td>$ {$venta['total']}</td>
                                    <td>
                                        <a href='/SolucionesWeb/Static/View/Admin/VentaProducto.php?accion=editar&id={$venta['idVenta']}' class='btn btn-primary'>Editar</a>

                                        <a href='../../Controller/Ventas.php?accion=eliminar&id={$venta['idVenta']}' class='btn btn-danger boton eliminar' >Eliminar</a>
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
            <div align="center">
                <a href="/SolucionesWeb/Static/View/Admin/ViewGestionTickets.php">
                    <button class="btn btn-fin">Consultar tickets</button>
                </a>
            </div>
            <div align="center">
                <form method="POST" onsubmit="return validateFinalizar(event);">
                    <input type="hidden" name="idNotaVenta" value="<?php echo $idNotaVenta; ?>"> <!-- Se pasa el idNotaVenta -->
                    <input type="hidden" name="noCliente" id="noClienteHidden">
                    <input type="hidden" name="noEmpleado" id="noEmpleadoHidden">
                    <button type="submit" class="btn btn-fin boton advertencia" id="btnFinalizar" onclick="mostrarModalFinalizar()">Finalizar compra</button>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" id="modalAdvertencia">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Advertencia</h5>
                <button type="button" class="btn-close" id="cerrarAdvertencia" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Mensaje de advertencia se actualizará aquí -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmarFinalizar">Confirmar</button>
            </div>
            </div>
        </div>
    </div>

    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <h2>Confirmar Eliminación</h2>
            <p>¿Estás seguro de que deseas eliminar este empleado? Esta acción no se puede deshacer.</p>
            <button id="confirmDelete" class="confirm">Confirmar</button>
            <button id="cancelDelete" class="cancel">Cancelar</button>
        </div>
    </div>

    <script>

        function filtrarVentas(query) {
            var rows = document.querySelectorAll("#tablaVentas tbody tr");
            rows.forEach(function(row) {
                var productName = row.cells[1].textContent.toLowerCase();
                if (productName.includes(query.toLowerCase())) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        function actualizarCampos() {
            document.getElementById("noClienteHidden").value = document.getElementById("noCliente").value;
            document.getElementById("noEmpleadoHidden").value = document.getElementById("noEmpleado").value;
        }

        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('modalAdvertencia');
            const confirmButton = document.getElementById('confirmarFinalizar');
            const closeButton = document.getElementById('cerrarAdvertencia');
            
            confirmButton.addEventListener('click', function () {
                document.querySelector("form").submit();
            });

            closeButton.addEventListener('click', function () {
                modal.style.display = 'none';
            });
        });

        function mostrarModalFinalizar() {
            // Al confirmar, proceder con la redirección
            document.getElementById('confirmarFinalizar').addEventListener('click', function () {
                // Actualizar los valores de los campos ocultos
                document.getElementById("noClienteHidden").value = document.getElementById("noCliente").value;
                document.getElementById("noEmpleadoHidden").value = document.getElementById("noEmpleado").value;

                // Crear la URL con los datos del formulario
                const idNotaVenta = document.querySelector('[name="idNotaVenta"]').value;
                const noCliente = document.getElementById("noClienteHidden").value;
                const noEmpleado = document.getElementById("noEmpleadoHidden").value;

                window.location.href = `/SolucionesWeb/Static/Controller/Tickets.php?idNotaVenta=${idNotaVenta}&noCliente=${noCliente}&noEmpleado=${noEmpleado}`;
            });
        }

        function validateFinalizar(event) {
            const modal = document.getElementById('modalAdvertencia');
            modal.style.display = 'block';
            // Validación de campos
            if (document.getElementById("noCliente").value === "") {
                document.querySelector('.modal-body').textContent = "Por favor, selecciona un cliente.";
                return false;
            }
            if (document.getElementById("noEmpleado").value === "") {
                document.querySelector('.modal-body').textContent = "Por favor, selecciona un empleado.";
                return false;
            }
            return true;
        }

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-qp1y2r6cXjl5G7pMwFZB93D9HzfgDWONhSmuED7B5buNxptDGa9wWLm3dhBB4dPY" crossorigin="anonymous"></script>
    <script src="../../Controller/Js/Eliminacion.js"></script>
    <script src="../../Controller/Js/Validaciones.js"></script>

</body>
</html>
