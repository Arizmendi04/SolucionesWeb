<?php include 'HeaderA.php'; ?>
<?php include '../../Controller/Connect/Db.php'; ?>
<?php include '../../Controller/Productos.php'; ?>
<?php include '../../Controller/Proveedores.php'; ?>

<!-- Recibir el id de la venta a editar-->
<?php
    if (isset($_GET['accion']) && $_GET['accion'] == 'editar') {
        $idVenta = isset($_GET['id']) ? $_GET['id'] : null;
    }
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/SolucionesWeb/Static/Css/ventaProducto.css">
</head>
<body>
    
    <div class="container-fluid">
        <div class="titulo text-center my-4">
            <h1>Productos</h1>
        </div>
            
        <!-- Contenedor de productos deslizante con buscador arriba -->
        <div class="contenedorProductos">
            <div class="busqueda mb-3">
                <input type="text" id="busqueda" placeholder="Buscar un producto por nombre" class="form-control" oninput="filtrarProductos(this.value)">
            </div>

            <div class="product-grid">
                <div class="product-list">
                    <?php
                    $productos = solicitarProductos($conn);
                    foreach ($productos as $producto) {
                        echo "
                        <div class='product-card p-3 m-2 border'>
                            <img src='/SolucionesWeb/Static/Img/Productos/{$producto['urlImagen']}' alt='Producto' class='img-fluid'>
                            <h5>{$producto['nombreProd']}</h5>
                            <p>Precio: \${$producto['precio']}</p>
                            <p>Peso: {$producto['peso']} {$producto['unidadM']}</p>
                            <p>Categoría: {$producto['tipo']}</p>
                            <p>Existencias: {$producto['existencia']}</p>
                            
                            

                            <!-- Mensaje de error debajo del producto -->
                            <div id='error-{$producto['folio']}' class='alert alert-danger' style='display: none;'>Por favor, ingresa una cantidad válida.</div>

                            <form method='get' action='/SolucionesWeb/Static/Controller/Ventas.php'>
                                <input type='hidden' name='idVenta' value='{$idVenta}'>
                                <input type='hidden' name='folioProducto' value='{$producto['folio']}'>
                                
                                <div style='display: flex; align-items: center;'>
                                    <label for='cantidad' style='margin-right: 8px;'>Cantidad:</label>
                                    <input type='tel' id='canti' name='cantidad' class='form-control canti'>
                                </div>

                                <input type='hidden' name='precio' value='{$producto['precio']}'>
                                
                                <button type='submit'>
                                    <img src='/SolucionesWeb/Static/Img/carrito.png' class='icono'>
                                </button>
                            </form>


                        </div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script src="/SolucionesWeb/Static/Controller/Js/Productos.js"></script>
    <script src="/SolucionesWeb/Static/Controller/Js/Carrito.js"></script>

</body>
</html>
