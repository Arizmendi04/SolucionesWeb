<?php include 'HeaderA.php' ?>
<?php include '../../Controller/Connect/Db.php'; ?>
<?php include '../../Controller/Productos.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/SolucionesWeb/Static/Css/Productos.css">
</head>
<body>
    <div class="container">
        <h1>Gestión de Productos</h1>

        <div class="row">
            <!-- Formulario de registro de productos -->
            <div class="col-md-4 formulario">
                <h2>Registrar Producto</h2>
                <form action="register_product.php" method="POST" enctype="multipart/form-data">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" required>

                    <label for="categoria">Categoría:</label>
                    <input type="text" id="categoria" name="categoria" class="form-control" required>

                    <label for="peso">Peso:</label>
                    <input type="text" id="peso" name="peso" class="form-control" required>

                    <label for="unidad">Unidad:</label>
                    <select id="unidad" name="unidad" class="form-control" required>
                        <option value="kg">Kilogramos</option>
                        <option value="lt">Litros</option>
                        <option value="g">Gramos</option>
                        <option value="ml">Mililitros</option>
                    </select>

                    <label for="precio">Precio:</label>
                    <input type="number" step="0.01" id="precio" name="precio" class="form-control" required>

                    <label for="cantidad">Existencias:</label>
                    <input type="number" id="cantidad" name="cantidad" class="form-control" required>

                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" class="form-control" required></textarea>

                    <label for="fotoProducto">Foto del Producto:</label>
                    <input type="file" id="fotoProducto" name="fotoProducto" class="form-control" accept="image/*" required>

                    <button type="submit" class="btn btn-primary mt-3" name="accion" value="crear">Registrar</button>
                </form>
            </div>

            <!-- Contenedor de productos deslizante -->
            <div class="col-md-8 product-grid">
                <div class="busqueda mb-3">
                    <input type="text" id="busqueda" placeholder="Buscar un producto" class="form-control" oninput="filtrarProductos(this.value)">
                </div>

                <div class="product-list">
                    <?php
                    $productos = solicitarProductos($conn);
                    foreach ($productos as $producto) {
                        echo "
                        <div class='product-card'>
                            <img src='{$producto['urlImagen']}' alt='Producto' class='img-fluid'>
                            <h5>{$producto['nombreProd']}</h5>
                            <p>\${$producto['precio']}</p>
                            <a href='modificarProducto.php?accion=editar&id={$producto['folio']}' class='btn btn-sm btn-secondary'>Editar</a>
                            <a href='delete_product.php?id={$producto['folio']}' class='btn btn-sm btn-danger'>Eliminar</a>
                        </div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script src="/SolucionesWeb/Static/Controller/Js/Productos.js"></script>
</body>
</html>
