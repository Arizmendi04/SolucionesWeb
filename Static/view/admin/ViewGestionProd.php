<?php include 'HeaderA.php'?>
<?php include '../../Controller/Productos.php'; ?>
<?php include '../../Controller/Connect/Db.php'; ?>
<?php include '../../Controller/Sesion.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Viga&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../Css/Crud.css">
</head>
<body>
    <div class="container">
        <h1>Gestión de Productos</h1>

        <div class="layout">
            <!-- Formulario de registro de producto -->
            <div class="formulario">
                <h2>Registrar Producto</h2>
                <form action="../../Controller/Productos.php" method="POST" enctype="multipart/form-data">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>
                    <p class="alert alert-danger" id="errorNombre" style="display:none;">
                        Ingresa un nombre válido, por favor.
                    </p>

                    <label for="categoria">Categoría:</label>
                    <input type="text" id="categoria" name="categoria" required>

                    <label for="precio">Precio:</label>
                    <input type="number" step="0.01" id="precio" name="precio" required>
                    <p class="alert alert-danger" id="errorPrecio" style="display:none;">Por favor ingresa un precio válido.</p>

                    <label for="cantidad">Cantidad:</label>
                    <input type="number" id="cantidad" name="cantidad" required>
                    <p class="alert alert-danger" id="errorCantidad" style="display:none;">Por favor ingresa una cantidad válida.</p>

                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" required></textarea>

                    <label for="fotoProducto">Foto del Producto:</label>
                    <input type="file" id="fotoProducto" name="fotoProducto" accept="image/*" required>

                    <button type="submit" name="accion" value="crear" onclick="return validacion();">Registrar</button>
                </form>
            </div>

            <!-- Tabla para consultar productos -->
            <div class="tabla">
                <!-- Barra de búsqueda de productos -->
                <div class="busqueda">
                    <h2>Lista de Productos</h2>
                    <input type="text" id="busqueda" placeholder="Buscar por nombre o categoría" oninput="filtrarProductos(this.value)">
                </div>

                <table id="tablaProductos">
                    <thead>
                        <tr>
                            <th>No. Producto</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $productos = solicitarProductos($conn);
                        foreach ($productos as $producto) {
                            echo "<tr>
                                <td>{$producto['noProducto']}</td>
                                <td>{$producto['nombre']}</td>
                                <td>{$producto['categoria']}</td>
                                <td>{$producto['precio']}</td>
                                <td>{$producto['cantidad']}</td>
                                <td>{$producto['descripcion']}</td>
                                <td>
                                    <div class='boton-contenedor'>
                                        <a href='/SolucionesWeb/Static/view/admin/modificarProducto.php?accion=editar&id={$producto['noProducto']}' class='boton editar'>Editar</a>
                                    <br><br>
                                        <a href='../../Controller/Productos.php?accion=eliminar&id={$producto['noProducto']}' class='boton eliminar'>Eliminar</a>
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

    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <h2>Confirmar Eliminación</h2>
            <p>¿Estás seguro de que deseas eliminar este producto? Esta acción no se puede deshacer.</p>
            <button id="confirmDelete" class="confirm">Confirmar</button>
            <button id="cancelDelete" class="cancel">Cancelar</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="../../Controller/Js/Productos.js"></script>
    <script src="../../Controller/Js/Validaciones.js"></script>

    <script>
        function filtrarProductos(query) {
            const tabla = document.getElementById("tablaProductos");
            const filas = tabla.getElementsByTagName("tr");

            for (let i = 1; i < filas.length; i++) {
                const celdas = filas[i].getElementsByTagName("td");
                let encontrado = false;

                // Solo verificar las celdas de Nombre y Categoría
                const nombre = celdas[1].innerText.toLowerCase(); // Celda de Nombre
                const categoria = celdas[2].innerText.toLowerCase(); // Celda de Categoría
                
                // Verificar si el query coincide con el nombre o categoría
                if (nombre.includes(query.toLowerCase()) || categoria.includes(query.toLowerCase())) {
                    encontrado = true;
                }
                
                filas[i].style.display = encontrado ? "" : "none";
            }
        }
    </script>
        
</body>
</html>
