<?php include 'HeaderA.php'; ?>
<?php include '../../Controller/Connect/Db.php'; ?>
<?php include '../../Controller/Productos.php'; ?>
<?php include '../../Controller/Proveedores.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Recepción</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/SolucionesWeb/Static/Css/Productos.css"> <!-- Referencia al CSS externo -->
</head>
<body>
    <div class="container">
        <h1>Gestión de Recepción de Productos</h1>

        <div class="row">
            <!-- Formulario de registro de recepción -->
            <div class="col-md-4 formulario">
                <h2>Registrar Recepción</h2>
                <form action="/SolucionesWeb/Static/Controller/Recepcion.php" method="POST">
                    
                    <label for="cantidadProducto">Cantidad de Producto:</label>
                    <input type="number" id="cantidadProducto" name="cantidadProducto" class="form-control" required>

                    <label for="fecha">Fecha de Recepción:</label>
                    <input type="date" id="fecha" name="fecha" class="form-control" required>

                    <label for="comentario">Comentario:</label>
                    <textarea id="comentario" name="comentario" class="form-control"></textarea>

                    <label for="Proveedor">Proveedor:</label>
                    <div class="busqueda mb-3">
                        <input type="text" id="proveedorNombre" placeholder="Busca un proveedor" class="form-control" autocomplete="off" oninput="buscarProveedor(this.value)">
                        <input type="hidden" id="idProveedor" name="idProveedor">
                    </div>
                    <div id="listaProveedores" class="list-group" style="display: none;">
                        <!-- Lista de proveedores generada dinámicamente -->
                        <?php
                        // Suponiendo que tienes un array de proveedores disponible
                        foreach ($proveedores as $proveedor) {
                            echo "<a href='javascript:void(0)' onclick='seleccionarProveedor({$proveedor['idProveedor']}, \"{$proveedor['nombre']}\")' class='list-group-item list-group-item-action'>{$proveedor['nombre']}</a>";
                        }
                        ?>
                    </div> <!-- Contenedor de la lista de proveedores -->

                    <label for="folio">Producto (Nombre):</label>
                    <select id="folio" name="folio" class="form-control" required>
                        <?php
                        // Suponiendo que tienes un array de productos disponible
                        foreach ($productos as $producto) {
                            echo "<option value='{$producto['folio']}'>{$producto['nombreProd']}</option>";
                        }
                        ?>
                    </select>

                    <!-- Botón de envío -->
                    <div class="botonRegistrar">
                        <button type="submit" class="btn btn-primary mt-3" name="accion" value="registrar">Registrar Recepción</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function seleccionarProveedor(id, nombre) {
            document.getElementById('idProveedor').value = id;
            document.getElementById('proveedorNombre').value = nombre;
            document.getElementById('listaProveedores').style.display = 'none';
        }

        function buscarProveedor(query) {
            // Implementar aquí la lógica para buscar proveedores
            document.getElementById('listaProveedores').style.display = query ? 'block' : 'none';
        }
    </script>
</body>
</html>
