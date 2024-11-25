<?php include 'HeaderA.php'; ?>
<?php include '../../Controller/Connect/Db.php'; ?>
<?php include '../../Controller/Productos.php'; ?>
<?php include '../../Controller/Proveedores.php'; ?>
<?php include '../../Controller/Recepciones.php'; ?>
<?php include '../../Controller/Sesion.php'; ?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CRUD de Recepción</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../Css/Crud.css">
        
    </head>
    <body>
        <div class="container">
            <h1>Gestión de Recepción de Productos</h1>

            <div class="layout">
                <!-- Formulario de registro de recepción -->
                <div class="formulario">
                    <h2>Registrar Recepción</h2>
                    <form action="/SolucionesWeb/Static/Controller/Recepciones.php" method="POST" enctype="multipart/form-data">
                        
                        <label for="cantidadProducto">Cantidad de Producto:</label>
                        <input type="number" id="cantidadProducto" name="cantidadProducto" class="form-control" required>
                        <p class="alert alert-danger" id="errorCantidad" style="display:none;">
                            Ingresa una cantidad válida, por favor.
                        </p>

                        <label for="fecha">Fecha de Recepción:</label>
                        <input type="date" id="fecha" name="fecha" class="form-control" required>
                        <p class="alert alert-danger" id="errorFecha" style="display:none;">Por favor ingresa una fecha con el formato dd/mm/aaaa.</p>

                        <label for="comentario">Comentario:</label>
                        <textarea id="comentario" name="comentario" class="form-control"></textarea>

                        <label for="proveedorNombre">Proveedor:</label>
                        <div class="busqueda">
                            <input type="text" id="proveedorNombre" placeholder="Busca un proveedor" class="form-control" autocomplete="off" oninput="buscarProveedor(this.value)">
                            <input type="hidden" id="idProveedor" name="idProveedor" required>
                        </div>
                        <p class="alert alert-danger" id="errorIdProveedor" style="display:none;">
                            Selecciona a un proveedor, por favor.
                        </p>

                        <div id="listaProveedores" class="list-group" style="display: none;">
                            <!-- Lista de proveedores generada dinámicamente -->
                            <?php
                            foreach ($proveedores as $proveedor) {
                                echo "<a href='javascript:void(0)' onclick='seleccionarProveedor({$proveedor['idProveedor']}, \"{$proveedor['nombre']}\")' class='list-group-item list-group-item-action'>{$proveedor['nombre']}</a>";
                            }
                            ?>
                        </div> <!-- Contenedor de la lista de proveedores -->

                        <label for="nombreProducto">Producto:</label>
                        <div class="busqueda">
                            <input type="text" id="nombre" placeholder="Busca un producto" class="form-control" autocomplete="off" oninput="buscarProducto(this.value, document.getElementById('idProveedor').value)">

                            <input type="hidden" id="idProducto" name="idProducto" required>
                        </div>
                        <p class="alert alert-danger" id="errorIdProducto" style="display:none;">
                            Selecciona un producto, por favor.
                        </p>
                        
                        <div id="listaProductos" class="list-group" style="display: none;">
                            <!-- Lista de productos generada dinámicamente -->
                            <?php
                            foreach ($productos as $producto) {
                                echo "<a href='javascript:void(0)' onclick='seleccionarProducto({$producto['folio']}, \"{$producto['nombreProd']}\")' class='list-group-item list-group-item-action'>{$producto['nombreProd']}</a>";
                            }
                            ?>
                        </div> <!-- Contenedor de la lista de productos -->

                        <!-- Botón de envío -->
                        <div class="botonRegistrar">
                            <button type="submit" class="btn btn-primary mt-3" name="accion" value="crear" onclick="return validacionRecepcion();">Registrar</button>
                        </div>
                    </form>
                </div>

                <!-- Tabla para consultar recepciones -->
                <div class="tabla">
                    <!-- Barra de búsqueda de recepciones -->
                    <div class="busqueda">
                        <h2>Lista de Recepciones</h2>
                        <input type="text" id="busqueda" placeholder="Buscar por fecha" oninput="filtrarRecepciones(this.value)">
                    </div>

                    <table id="tablaRecepciones">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cantidad Productos</th>
                                <th>Fecha</th>
                                <th>Comentario</th>
                                <th>Folio Producto</th>
                                <th style="display:none;">Proveedor (ID)</th>
                                <th>Proveedor</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $recepciones = solicitarRecepciones($conn);
                                foreach ($recepciones as $recepcion) {
                                    // Obtener el nombre del proveedor
                                    $proveedor = obtenerProveedorPorID($conn, $recepcion['idProveedor']); // Asegúrate de que esta función existe
                                    echo "<tr>
                                        <td>{$recepcion['idRep']}</td>
                                        <td>{$recepcion['cantidadProducto']}</td>
                                        <td>{$recepcion['fecha']}</td>
                                        <td>{$recepcion['comentario']}</td>
                                        <td>{$recepcion['folio']}</td>
                                        <td style='display:none;'>{$proveedor['idProveedor']}</td>
                                        <td>{$proveedor['razonSocial']}</td>
                                        <td>
                                            <div class='boton-contenedor'>
                                                <a href='/SolucionesWeb/Static/view/admin/modificarRecepcion.php?accion=editar&id={$recepcion['idRep']}' class='boton editar'>Editar</a>
                                                
                                                <a href='/SolucionesWeb/Static/Controller/Recepciones.php?accion=eliminar&id={$recepcion['idRep']}' class='boton eliminar'>Eliminar</a>
                                            </div>
                                        </td>
                                    </tr>";

                                }
                            ?>
                        </tbody>
                    </table>
                </div>


                
            </div>
        </div>

        <!-- Modal de Confirmación -->
        <div id="confirmModal" class="modal">
            <div class="modal-content">
                <h2>Confirmar Eliminación</h2>
                <p>¿Estás seguro de que deseas eliminar este recepción? Esta acción no se puede deshacer.</p>
                <button id="confirmDelete" class="confirm">Confirmar</button>
                <button id="cancelDelete" class="cancel">Cancelar</button>
            </div>
        </div>

        <script src="/SolucionesWeb/Static/Controller/Js/Recepciones.js"></script>
        <script src="/SolucionesWeb/Static/Controller/Js/ConfirmElim.js"></script>
        <script src="/SolucionesWeb/Static/Controller/Js/Validaciones.js"></script>

        <script>
            window.onload = function() {
                ocultarColumnaProveedor();
            };

            function ocultarColumnaProveedor() {
                // Obtener todas las celdas de la columna "Proveedor (ID)"
                const tabla = document.getElementById("tablaRecepciones");
                const headers = tabla.getElementsByTagName("th");
                const rows = tabla.getElementsByTagName("tr");

                // Recorre el encabezado y oculta la columna correspondiente
                for (let i = 0; i < headers.length; i++) {
                    if (headers[i].innerText === 'Proveedor (ID)') {
                        // Ocultar la columna en el encabezado
                        headers[i].style.display = 'none';
                        
                        // Ocultar la columna en cada fila
                        for (let row of rows) {
                            row.getElementsByTagName("td")[i].style.display = 'none';
                        }
                        break;
                    }
                }
            }
                function filtrarRecepciones(query) {
                    const tabla = document.getElementById("tablaRecepciones");
                    const filas = tabla.getElementsByTagName("tr");

                    // Convertir el query a minúsculas para hacer la búsqueda insensible a mayúsculas
                    const queryLower = query.toLowerCase();

                    for (let i = 1; i < filas.length; i++) {
                        const celdas = filas[i].getElementsByTagName("td");
                        const fechaRecepcion = celdas[2].innerText.toLowerCase(); // Celda de Fecha de Recepción 

                        // Verificar si la fecha completa contiene el query
                        const encontrado = fechaRecepcion.includes(queryLower);

                        // Mostrar la fila si se encontró coincidencia, ocultarla si no
                        filas[i].style.display = encontrado ? "" : "none";
                    }
                }
        </script>


    </body>
</html>
