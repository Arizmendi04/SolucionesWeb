<?php include 'HeaderE.php'?> 
<?php include '../../Controller/ControllerEmpleado/Proveedores.php'; ?>
<?php include '../../Controller/Connect/Db.php'; ?>
<?php include '../../Controller/Sesion.php'; ?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CRUD de Proveedores</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Viga&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../../Css/Crud.css">
    </head>
    <body>
        <div class="container">
            <h1>Gestión de Proveedores</h1>

            <div class="layout">

                <!-- Tabla para consultar proveedores -->
                <div class="tabla">
                    <!-- Barra de búsqueda de proveedores -->
                    <div class="busquedaE">
                        <h2>Lista de Proveedores</h2>
                        <input type="text" id="busqueda" placeholder="Buscar por razón social o nombre comercial" oninput="filtrarProveedores(this.value)">
                    </div>

                    <table id="tablaProveedores">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Razón Social</th>
                                <th>Nombre Comercial</th>
                                <th>Teléfono</th>
                                <th>Correo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $proveedores = solicitarProveedores($conn);
                            foreach ($proveedores as $proveedor) {
                                echo "<tr>
                                    <td>{$proveedor['idProveedor']}</td>
                                    <td>{$proveedor['razonSocial']}</td>
                                    <td>{$proveedor['nombreComercial']}</td>
                                    <td>{$proveedor['telefono']}</td>
                                    <td>{$proveedor['correo']}</td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="../../Controller/Js/Validaciones.js"></script>

        <script>
            function filtrarProveedores(query) {
                const tabla = document.getElementById("tablaProveedores");
                const filas = tabla.getElementsByTagName("tr");

                for (let i = 1; i < filas.length; i++) {
                    const celdas = filas[i].getElementsByTagName("td");
                    let encontrado = false;

                    // Solo verificar las celdas de Razón Social y Nombre Comercial
                    const razonSocial = celdas[1].innerText.toLowerCase(); // Celda de Razón Social
                    const nombreComercial = celdas[2].innerText.toLowerCase(); // Celda de Nombre Comercial
                    
                    // Verificar si el query coincide con la razón social o nombre comercial
                    if (razonSocial.includes(query.toLowerCase()) || nombreComercial.includes(query.toLowerCase())) {
                        encontrado = true;
                    }
                    
                    filas[i].style.display = encontrado ? "" : "none";
                }
            }
        </script>
            
    </body>
</html>
