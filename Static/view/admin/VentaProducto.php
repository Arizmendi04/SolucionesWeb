<?php include 'HeaderA.php'; ?>
<?php include '../../Controller/Connect/Db.php'; ?>
<?php include '../../Controller/Productos.php'; ?>
<?php include '../../Controller/Proveedores.php'; ?>

<?php
// Verificamos si el idVenta fue enviado
$idVenta = isset($_POST['idVenta']) ? $_POST['idVenta'] : null;
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
                            
                            <div style='display: flex; align-items: center;'>
                                <label for='cantidad' style='margin-right: 8px;'>Cantidad:</label>
                                <input type='tel' id='cantidad' class='form-control canti'>
                            </div>

                            <!-- Campo oculto para almacenar idVenta -->
                            <input type='hidden' class='idVenta' value='{$idVenta}'>
                            
                            <!-- Mensaje de error debajo del producto -->
                            <div id='error-{$producto['folio']}' class='alert alert-danger' style='display: none;'>Por favor, ingresa una cantidad válida.</div>

                            <a href='javascript:void(0);' class='btn btn-sm' id='agregarCarrito' onclick='agregarAlCarrito({$idVenta}, {$producto['folio']})'>
                                <img src='/SolucionesWeb/Static/Img/carrito.png' alt='Carrito' class='icono'>
                            </a>
                        </div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <h2>Confirmar Acción</h2>
            <p>¿Estás seguro de que deseas agregar este producto al carrito?</p>
            <button id="confirmAdd" class="btn btn-success">Confirmar</button>
            <button id="cancelAdd" class="btn btn-danger">Cancelar</button>
        </div>
    </div>

    <div id="mensajeExito" class="alert alert-success" name="mensajeExito" style="display: none;">
        <span id="mensajeTexto"></span>
    </div>

    <script src="/SolucionesWeb/Static/Controller/Js/Productos.js"></script>
    <script src="/SolucionesWeb/Static/Controller/Js/Carrito.js"></script>

    <script>
        // Función para agregar al carrito y hacer el update en la base de datos
        function agregarAlCarrito(idVenta, folioProducto) {
            // Obtenemos la cantidad ingresada
            const cantidad = document.querySelector(`#cantidad`).value;
            const errorMessage = document.querySelector(`#error-${folioProducto}`);
            
            if (cantidad > 0) {
                // Ocultar error si la cantidad es válida
                errorMessage.style.display = 'none';

                // Realizamos la llamada AJAX para hacer el update en la base de datos
                fetch('/SolucionesWeb/Static/Controller/Ventas.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `idVenta=${idVenta}&folioProducto=${folioProducto}&cantidad=${cantidad}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mostrar mensaje de éxito
                        document.getElementById('mensajeExito').style.display = 'block';
                        document.getElementById('mensajeTexto').textContent = 'Producto agregado al carrito con éxito';
                    } else {
                        // Manejar errores
                        alert('Hubo un error al agregar el producto al carrito');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            } else {
                // Mostrar mensaje de error debajo del producto
                errorMessage.style.display = 'block';
            }
        }
    </script>
</body>
</html>
