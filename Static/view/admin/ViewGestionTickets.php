<?php include 'HeaderA.php'?> 
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
                        <input type="text" id="busqueda" placeholder="Buscar por fecha" oninput="filtrarVentas(this.value)">
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
                            </tr>
                        </thead>
                        <tbody id="resultadosVentas">
                            <?php include '../../Controller/Tickets.php'; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Botón para generar ticket -->
                <button class="btn-generar-ticket btn btn-success" onclick="generarTicket()">Generar Ticket</button>
            </div>
        </div>

        <script>
            function filtrarVentas(fecha) {
                const xhr = new XMLHttpRequest();
                // Si el campo está vacío, obtiene todos los registros
                const url = fecha ? `/SolucionesWeb/Static/Controller/Tickets.php?accion=filtrar&fecha=${fecha}` : `/SolucionesWeb/Static/Controller/Tickets.php?accion=filtrar`;

                xhr.open("GET", url, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        document.getElementById("resultadosVentas").innerHTML = xhr.responseText;
                    }
                };
                xhr.send();
            }

            function generarTicket() {
                window.location.href = '/SolucionesWeb/Static/View/Admin/ViewGestionVent.php';
            }

            // Cargar todos los registros al cargar la página
            document.addEventListener("DOMContentLoaded", function() {
                filtrarVentas('');
            });
        </script>
    </body>
</html>
