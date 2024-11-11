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

        <div class="layout">
            <!-- Formulario de registro de venta -->
            <div class="formulario">
                <h2 align="center">Registrar Venta</h2>
                <form action="../../Controller/Ventas.php" method="POST">
                    <label for="idCliente">Cliente:</label>
                    <select id="idCliente" name="idCliente" required>
                        <?php
                        // Consulta para obtener clientes
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
                    
                    <label for="idProducto">Producto:</label>
                    <select id="idProducto" name="idProducto" required>
                        <?php
                        // Consulta para obtener productos
                        $queryProductos = "SELECT folio, nombreProd, precio FROM producto";
                        $resultProductos = $conn->query($queryProductos);

                        if ($resultProductos && $resultProductos->num_rows > 0) {
                            while ($producto = $resultProductos->fetch_assoc()) {
                                echo "<option value='{$producto['folio']}'>{$producto['nombreProd']} - $ {$producto['precio']}</option>";
                            }
                        } else {
                            echo "<option value=''>No hay productos disponibles</option>";
                        }
                        ?>
                    </select>
                    
                    <label for="cantidad">Cantidad:</label>
                    <input type="number" id="cantidad" name="cantidad" required>

                    <button type="submit" name="accion" value="crearVenta">Registrar Venta</button>
                </form>
            </div>

            <!-- Tabla para consultar ventas -->
            <div class="tabla">
                <div class="busqueda">
                    <h2 align="center">Ticket</h2>
                    <input type="text" id="busqueda" placeholder="Buscar por cliente o producto" oninput="filtrarVentas(this.value)">
                </div>
                <table id="tablaVentas">
                    <thead>
                        <tr>
                            <th>ID Venta</th>
                            <th>Cliente</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
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
