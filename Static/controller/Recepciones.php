<?php include 'Connect/Db.php'; ?>
<?php include 'Sesion.php'; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/SolucionesWeb/Static/Model/Recepcion.php'); ?>

<?php
    function solicitarRecepciones($conn) {
        $Recepcion = new Recepcion($conn);
        $resultado = $Recepcion->obtenerRecepcions();
        return $resultado;
    }

    function filtrarRecepciones($conn, $parametro) {
        $Recepcion = new Recepcion($conn);
        $resultado = $Recepcion->filtrarRecepcion($parametro);
        return $resultado;
    }

    function obtenerRecepcionPorID($conn, $idRecepcion) {
        $Recepcion = new Recepcion($conn);
        return $Recepcion->obtenerRecepcion($idRecepcion);
    }

    // Crear Recepcion
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['accion'] == 'crear') {
        // Obtener los datos del formulario
        $Recepcion = new Recepcion($conn);
        $Recepcion->setNombre($_POST['nombre']);
        $Recepcion->setApellido($_POST['apellido']);
        $Recepcion->setSexo($_POST['sexo']);
        $Recepcion->setFechaNac($_POST['fechaNac']);
        $Recepcion->setFechaIngreso($_POST['fechaIngreso']);
        $Recepcion->setSueldo($_POST['sueldo']);
        $Recepcion->setCargo($_POST['cargo']);
        $Recepcion->setTelefono($_POST['telefono']);
        $Recepcion->setDireccion($_POST['direccion']);

        // Manejar la subida de la imagen de perfil
        if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] == 0) {
            $fotoPerfil = $_FILES['fotoPerfil'];
            $nombreArchivo = basename($fotoPerfil['name']);
            $rutaDestino = '../Img/User/' . $nombreArchivo;
            $tipoArchivo = strtolower(pathinfo($rutaDestino, PATHINFO_EXTENSION));

            if (in_array($tipoArchivo, ['jpg', 'jpeg', 'png', 'gif'])) {
                if (move_uploaded_file($fotoPerfil['tmp_name'], $rutaDestino)) {
                    $Recepcion->setFotoPerfil($rutaDestino); // Establecer la ruta de la foto de perfil

                    // Intentar insertar el Recepcion y manejar excepciones
                    try {
                        $Recepcion->insertarRecepcion();
                        header('Location: ../View/Admin/ViewGestionEmp.php');
                        exit; // Asegurarse de salir después de redirigir
                    } catch (mysqli_sql_exception $e) {
                        // Capturar y manejar la excepción
                        echo "Error al insertar el Recepcion: " . $e->getMessage();
                    }
                } else {
                    echo "Error al subir la imagen.";
                }
            } else {
                echo "Solo se permiten archivos con las siguientes extensiones: JPG, JPEG, PNG, GIF.";
            }
        } else {
            echo "Error: Debes subir una foto de perfil.";
        }
    }

    // Modificar Recepcion
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['accion'] == 'actualizar') {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $RecepcionData = obtenerRecepcionPorID($conn, $id);

            if ($RecepcionData) {
                // Crear objeto Recepcion con los datos actuales y nuevos del formulario
                $Recepcion = new Recepcion($conn);
                $Recepcion->setNoRecepcion($id);
                $Recepcion->setNombre($_POST['nombre'] ?? $RecepcionData['nombre']);
                $Recepcion->setApellido($_POST['apellido'] ?? $RecepcionData['apellido']);
                $Recepcion->setSexo($_POST['sexo'] ?? $RecepcionData['sexo']);
                $Recepcion->setFechaNac($_POST['fechaNac'] ?? $RecepcionData['fechaNac']);
                $Recepcion->setFechaIngreso($_POST['fechaIngreso'] ?? $RecepcionData['fechaIngreso']);
                $Recepcion->setSueldo($_POST['sueldo'] ?? $RecepcionData['sueldo']);
                $Recepcion->setCargo($_POST['cargo'] ?? $RecepcionData['cargo']);
                $Recepcion->setTelefono($_POST['telefono'] ?? $RecepcionData['telefono']);
                $Recepcion->setDireccion($_POST['direccion'] ?? $RecepcionData['direccion']);
                $Recepcion->setFotoPerfil($RecepcionData['urlFotoPerfil']); // Mantener imagen actual

                // Manejo de nueva imagen de perfil
                if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] == 0) {
                    $fotoPerfil = $_FILES['fotoPerfil'];
                    $nombreArchivo = basename($fotoPerfil['name']);
                    $rutaDestino = '../Img/User/' . $nombreArchivo;
                    $tipoArchivo = strtolower(pathinfo($rutaDestino, PATHINFO_EXTENSION));
                    
                    if (in_array($tipoArchivo, ['jpg', 'jpeg', 'png', 'gif']) && move_uploaded_file($fotoPerfil['tmp_name'], $rutaDestino)) {
                        $Recepcion->setFotoPerfil($rutaDestino); // Actualizar con nueva imagen
                    } else {
                        echo "Error al subir la nueva imagen.";
                        exit;
                    }
                }

                // Ejecutar la actualización en base de datos
                $respuesta = $Recepcion->modificarRecepcion(); // Llamar al método de modificación
                if ($respuesta) {
                    header('Location: ../View/Admin/ViewGestionEmp.php');
                    exit; // Asegurarse de salir después de redirigir
                }
            } else {
                echo "No se encontró el Recepcion especificado.";
            }
        } else {
            echo "ID de Recepcion no especificado.";
        }
    }

    // Eliminar Recepcion
    if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar') {
        $id = $_GET['id'];
        $Recepcion = new Recepcion($conn);
        $resultado = $Recepcion->eliminarRecepcion($id); // Llamar al método para eliminar Recepcion
        if ($resultado) {
            header('Location: ../View/Admin/ViewGestionEmp.php');
            exit; // Asegurarse de salir después de redirigir
        } else {
            echo "Error al eliminar Recepcion: " . $conn->error;
        }
    }

?>
