<?php include 'Connect/Db.php'; ?>
<?php include 'Sesion.php'; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/SolucionesWeb/Static/Model/Producto.php'); ?>

<?php
    function solicitarProductos($conn) {
        $Producto = new Producto($conn);
        return $Producto->obtenerProductos();
    }

    function filtrarProductos($conn, $parametro) {
        $Producto = new Producto($conn);
        return $Producto->filtrarProducto($parametro);
    }

    function obtenerProductoPorID($conn, $folioProducto) {
        $Producto = new Producto($conn);
        return $Producto->obtenerProducto($folioProducto);
    }

    // Crear Producto
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['accion'] == 'crear') {
        // Obtener los datos del formulario
        $producto = new Producto($conn);
        $producto->setNombreProd($_POST['nombre']);
        $producto->setDescripcion($_POST['descripcion']);
        $producto->setPrecio($_POST['precio']);
        $producto->setExistencia($_POST['cantidad']);
        $producto->setTipo($_POST['categoria']); // Agregado para incluir categoría
        $producto->setPeso($_POST['peso']); // Agregado para incluir peso
        $producto->setUnidadM($_POST['unidad']); // Agregado para incluir unidad
        $producto->setIdProveedor($_POST['idProveedor']);


        // Manejar la subida de la imagen
        if (isset($_FILES['fotoProducto']) && $_FILES['fotoProducto']['error'] == 0) {
            $imagen = $_FILES['fotoProducto'];
            $nombreArchivo = basename($imagen['name']);
            $rutaDestino = '../Img/Productos/' . $nombreArchivo; // Ruta donde se guardará la imagen

            $tipoArchivo = strtolower(pathinfo($rutaDestino, PATHINFO_EXTENSION));

            if (in_array($tipoArchivo, ['jpg', 'jpeg', 'png', 'gif'])) {
                if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
                    // Solo se guarda el nombre del archivo, no la ruta
                    $producto->setUrlImagen($nombreArchivo); // Establecer solo el nombre de la imagen

                    // Intentar insertar el Producto y manejar excepciones
                    try {
                        $producto->insertarProducto();
                        header('Location: /SolucionesWeb/Static/View/Admin/ViewGestionProd.php');
                        exit; // Asegurarse de salir después de redirigir
                    } catch (mysqli_sql_exception $e) {
                        echo "Error al insertar el producto: " . $e->getMessage();
                    }
                } else {
                    echo "Error al subir la imagen.";
                }
            } else {
                echo "Solo se permiten archivos con las siguientes extensiones: JPG, JPEG, PNG, GIF.";
            }
        } else {
            echo "Error: Debes subir una imagen.";
        }
    }

    // Modificar Producto
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['accion'] == 'actualizar') {
        if (isset($_POST['folio'])) {
            $folio = $_POST['folio'];
            $ProductoData = obtenerProductoPorID($conn, $folio);

            if ($ProductoData) {
                // Crear objeto Producto con los datos actuales y nuevos del formulario
                $Producto = new Producto($conn);
                $Producto->setFolio($folio);
                $Producto->setNombreProd($_POST['nombre'] ?? $ProductoData['nombreProd']);
                $Producto->setDescripcion($_POST['descripcion'] ?? $ProductoData['descripcion']);
                $Producto->setPrecio($_POST['precio'] ?? $ProductoData['precio']);
                $Producto->setExistencia($_POST['cantidad'] ?? $ProductoData['existencia']);
                $Producto->setTipo($_POST['categoria'] ?? $ProductoData['tipo']); // Agregado para incluir categoría
                $Producto->setPeso($_POST['peso'] ?? $ProductoData['peso']); // Agregado para incluir peso
                $Producto->setUnidadM($_POST['unidad'] ?? $ProductoData['unidadM']); // Agregado para incluir unidad
                $Producto->setUrlImagen($ProductoData['urlImagen']); // Mantener imagen actual

                // Manejo de nueva imagen
                if (isset($_FILES['fotoProducto']) && $_FILES['fotoProducto']['error'] == 0) {
                    $imagen = $_FILES['fotoProducto'];
                    $nombreArchivo = basename($imagen['name']);
                    $rutaDestino = '../Img/Product/' . $nombreArchivo;
                    $tipoArchivo = strtolower(pathinfo($rutaDestino, PATHINFO_EXTENSION));

                    if (in_array($tipoArchivo, ['jpg', 'jpeg', 'png', 'gif']) && move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
                        $Producto->setUrlImagen($nombreArchivo); // Actualizar con nueva imagen (solo nombre)
                    } else {
                        echo "Error al subir la nueva imagen.";
                        exit;
                    }
                }

                // Ejecutar la actualización en base de datos
                if ($Producto->modificarProducto()) {
                    header('Location: ../View/Admin/ViewGestionProd.php');
                    exit; 
                }
            } else {
                echo "No se encontró el Producto especificado.";
            }
        } else {
            echo "Folio de Producto no especificado.";
        }
    }

    // Eliminar Producto
    if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar') {
        $folio = $_GET['id'];
        $producto = new Producto($conn);
        
        // Intentar eliminar el producto
        $resultado = $producto->eliminarProducto($folio);
        if ($resultado) {
            header('Location: /SolucionesWeb/Static/View/Admin/ViewGestionProd.php');
            exit; 
        } else {
            echo "Error al eliminar Producto: " . $conn->error;
        }
    }

    // Buscar productos
    if (isset($_GET['accion']) && $_GET['accion'] == 'filtrar') {
        $nombreProducto = isset($_GET['nombre']) ? $_GET['nombre'] : '';
        // Proteger contra inyecciones SQL
        $nombreProducto = $conn->real_escape_string($nombreProducto);
        // Llamar a la función de filtrado
        $resultado = filtrarProductos($conn, $nombreProducto);
        // Generar la respuesta en HTML
        if ($resultado && count($resultado) > 0) {
            foreach ($resultado as $producto) {
                // Muestra el producto en el formato "folio -> nombreProd"
                echo "<div class='list-group-item' onclick='seleccionarProducto(\"{$producto['folio']}\", \"{$producto['nombreProd']}\")'>{$producto['folio']} -> {$producto['nombreProd']}</div>";
            }
        } else {
            echo "<div>No se encontraron productos.</div>";
        }
    }

?>
