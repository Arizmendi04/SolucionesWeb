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

        <label for="idCliente">Cliente:</label>
        <select id="idCliente" name="idCliente" required>
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

        <label for="idEmpleado">Empleado:</label>

        <select id="idEmpleado" name="idEmpleado" required>
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

            <!-- Tabla para consultar ventas -->
            <div class="tabla">
                <div class="busqueda">
                    <h2 align="center">Ticket</h2>
                    <input type="text" id="busqueda" placeholder="Buscar por cliente o producto" oninput="filtrarVentas(this.value)">
                    <p>Folio: </p>
                </div>
                <table id="tablaVentas">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Producto</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Consulta para obtener ventas
                        $queryVentas = "SELECT v.idNotaVenta, c.nombreC AS clienteNombre, p.nombreProd AS productoNombre, v.pagoTotal, p.precio 
                                        FROM notaventa v 
                                        JOIN cliente c ON v.noCliente = c.noCliente
                                        JOIN producto p";
                        $resultVentas = $conn->query($queryVentas);

                        if ($resultVentas && $resultVentas->num_rows > 0) {
                            while ($venta = $resultVentas->fetch_assoc()) {
                                $total = $venta['precio'] * $venta['cantidad'];
                                echo "<tr>
                                    <td>{$venta['idVenta']}</td>
                                    <td>{$venta['clienteNombre']}</td>
                                    <td>{$venta['productoNombre']}</td>
                                    <td>{$venta['cantidad']}</td>
                                    <td>$ {$total}</td>
                                    <td>
                                        <a href='modificarVenta.php?accion=editar&id={$venta['idVenta']}' class='btn btn-primary'>Editar</a>
                                        <a href='../../Controller/Ventas.php?accion=eliminar&id={$venta['idVenta']}' class='btn btn-danger' onclick='return confirm(\"¿Estás seguro de eliminar esta venta?\")'>Eliminar</a>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No hay ventas registradas</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script src="../../Controller/Js/ConfirmElim.js"></script>
    <script src="../../Controller/Js/Validaciones.js"></script>
</body>
</html>
