<?php include 'HeaderA.php'; ?>
<?php include '../../Controller/Connect/Db.php'; ?>
<?php include '../../Controller/Sesion.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6hW+ALEwIH" crossorigin="anonymous">
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
            <option value="">Selecciona un cliente</option>
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

        <br>

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
                                        <input type='tel' class='entradaC' value='{$venta['cantidad']}' 
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
            <form method="POST" action="/SolucionesWeb/Static/Controller/Tickets.php">
                <input type="hidden" name="idNotaVenta" value="<?php echo $idNotaVenta; ?>"> <!-- Se pasa el idNotaVenta -->
                
                <input type="hidden" name="noCliente" id="noClienteHidden">
                <input type="hidden" name="noEmpleado" id="noEmpleadoHidden" value="<?php echo $_SESSION['noEmpleado']; ?>">
                
                <button type="button" class="btn btn-fin" id="btnFinalizar" onclick="validarFormulario()">Finalizar compra</button>
                <input type="hidden" name="finalizarCompra" value="1"> <!-- Campo oculto para identificar la acción -->
            </form>
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

        function validarFormulario() {
            // Prevenir el envío del formulario por defecto
            event.preventDefault();

            // Obtener los valores de cliente y empleado
            const noCliente = document.getElementById("noCliente").value;
            const noEmpleado = document.getElementById("noEmpleadoHidden").value;
            
            // Verificar si hay filas válidas en la tabla de ventas
            const tablaVentas = document.getElementById("tablaVentas").querySelectorAll("tbody tr");
            let hayVentasValidas = false;
            tablaVentas.forEach(row => {
                if (row.style.display !== 'none' && row.querySelector('td').innerText.trim() !== 'No hay productos en la venta actual') {
                    hayVentasValidas = true;
                }
            });

            // Validaciones
            if (!noCliente || noCliente.trim() === "") {
                alert("Por favor, seleccione un cliente.");
                return;
            }
            if (!hayVentasValidas) {
                alert("Debe haber al menos una venta válida en la tabla.");
                return;
            }
            
            // Si todo es válido, enviar el formulario
            document.querySelector('form').submit();
        }

        function actualizarCampos() {
            document.getElementById("noClienteHidden").value = document.getElementById("noCliente").value;
        }

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

        function enviarDatos(event, inputElement, folioProd, cantidad, precio) {
            event.preventDefault();
            if (cantidad.trim() === '' || isNaN(cantidad) || parseFloat(cantidad) <= 0) {
                alert('Por favor ingrese una cantidad válida.');
                return;
            }
            const fila = inputElement.closest('tr');
            const idVenta = fila.cells[0].innerText
            .trim();
            const total = parseFloat(cantidad) * parseFloat(precio);

            // Actualizar la celda de total
            fila.cells[4].innerText = '$ ' + total.toFixed(2);

            // Enviar datos al servidor por AJAX
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/SolucionesWeb/Static/Controller/Ventas.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log('Respuesta del servidor:', xhr.responseText);
                }
            };
            xhr.send('accion=actualizar&idVenta=' + encodeURIComponent(idVenta) + '&cantidad=' + encodeURIComponent(cantidad));
        }
    </script>
</body>
</html>
