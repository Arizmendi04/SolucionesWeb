<?php include 'Connect/Db.php'; ?>
<?php include 'Sesion.php'; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/SolucionesWeb/Static/Model/Producto.php'); ?>

<?php
    function solicitarProductos($conn) {
        $Producto = new Producto($conn);
        $resultado = $Producto->obtenerProductos();
        return $resultado;
    }

    function filtrarProductos($conn, $parametro) {
        $Producto = new Producto($conn);
        $resultado = $Producto->filtrarProducto($parametro);
        return $resultado;
    }

    function obtenerProductoPorID($conn, $folioProducto) {
        $Producto = new Producto($conn);
        return $Producto->obtenerProducto($folioProducto);
    }

    // Crear Producto
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['accion'] == 'crear') {
        // Obtener los datos del formulario
        $Producto = new Producto($conn);
        $Producto->setNombreProd($_POST['nombre']); // Cambia según tu modelo
        $Producto->setDescripcion($_POST['descripcion']); // Cambia según tu modelo
        $Producto->setPrecio($_POST['precio']); // Cambia según tu modelo
        $Producto->setExistencia($_POST['cantidad']); // Cambia según tu modelo
        // No se incluye 'categoria' ya que no está en el modelo proporcionado

        // Manejar la subida de la imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $imagen = $_FILES['imagen'];
            $nombreArchivo = basename($imagen['name']);
            $rutaDestino = '../Img/Product/' . $nombreArchivo;
            $tipoArchivo = strtolower(pathinfo($rutaDestino, PATHINFO_EXTENSION));

            if (in_array($tipoArchivo, ['jpg', 'jpeg', 'png', 'gif'])) {
                if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
                    $Producto->setUrlImagen($rutaDestino); // Establecer la ruta de la imagen

                    // Intentar insertar el Producto y manejar excepciones
                    try {
                        $Producto->insertarProducto();
                        header('Location: ../View/Admin/ViewGestionProd.php');
                        exit; // Asegurarse de salir después de redirigir
                    } catch (mysqli_sql_exception $e) {
                        // Capturar y manejar la excepción
                        echo "Error al insertar el Producto: " . $e->getMessage();
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
                $Producto->setFolio($folio); // Usar setFolio
                $Producto->setNombreProd($_POST['nombre'] ?? $ProductoData['nombreProd']);
                $Producto->setDescripcion($_POST['descripcion'] ?? $ProductoData['descripcion']);
                $Producto->setPrecio($_POST['precio'] ?? $ProductoData['precio']);
                $Producto->setExistencia($_POST['cantidad'] ?? $ProductoData['existencia']);
                $Producto->setUrlImagen($ProductoData['urlImagen']); // Mantener imagen actual

                // Manejo de nueva imagen
                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                    $imagen = $_FILES['imagen'];
                    $nombreArchivo = basename($imagen['name']);
                    $rutaDestino = '../Img/Product/' . $nombreArchivo;
                    $tipoArchivo = strtolower(pathinfo($rutaDestino, PATHINFO_EXTENSION));
                    
                    if (in_array($tipoArchivo, ['jpg', 'jpeg', 'png', 'gif']) && move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
                        $Producto->setUrlImagen($rutaDestino); // Actualizar con nueva imagen
                    } else {
                        echo "Error al subir la nueva imagen.";
                        exit;
                    }
                }

                // Ejecutar la actualización en base de datos
                $respuesta = $Producto->modificarProducto(); // Llamar al método de modificación
                if ($respuesta) {
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
        $folio = $_GET['id']; // Cambiado de 'folio' a 'id'
        $producto = new Producto($conn);
        
        // Intentar eliminar el producto
        $resultado = $producto->eliminarProducto($folio);
        if ($resultado) {
            header('Location: /SolucionesWeb/Static/View/Admin/ViewGestionProd.php');
            exit; // Asegúrate de salir después de redirigir
        } else {
            echo "Error al eliminar Producto: " . $conn->error; // Mensaje de error
        }
    }

?>
