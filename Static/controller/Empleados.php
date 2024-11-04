<?php include 'Connect/Db.php'; ?>
<?php include 'Sesion.php'; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/SolucionesWeb/Static/Model/Empleado.php'); ?>

<?php
    function solicitarEmpleados($conn) {
        $empleado = new Empleado($conn);
        $resultado = $empleado->obtenerEmpleados();
        return $resultado;
    }

    function filtrarEmpleados($conn, $parametro) {
        $empleado = new Empleado($conn);
        $resultado = $empleado->filtrarEmpleado($parametro);
        return $resultado;
    }

    function obtenerEmpleadoPorID($conn, $idEmpleado) {
        $empleado = new Empleado($conn);
        return $empleado->obtenerEmpleado($idEmpleado);
    }

    // Crear empleado
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['accion'] == 'crear') {
        // Obtener los datos del formulario
        $empleado = new Empleado($conn);
        $empleado->setNombre($_POST['nombre']);
        $empleado->setApellido($_POST['apellido']);
        $empleado->setSexo($_POST['sexo']);
        $empleado->setFechaNac($_POST['fechaNac']);
        $empleado->setFechaIngreso($_POST['fechaIngreso']);
        $empleado->setSueldo($_POST['sueldo']);
        $empleado->setCargo($_POST['cargo']);
        $empleado->setTelefono($_POST['telefono']);
        $empleado->setDireccion($_POST['direccion']);

        // Verificar si el empleado ya existe
        if ($empleado->existeEmpleado()) {
            $_SESSION['error'] = "El empleado ya está registrado. Recarga la página para quitar el mensaje";
            header('Location: ../View/Admin/ViewGestionEmp.php'); // Redirigir de vuelta a la página
            exit;
        }

        // Manejar la subida de la imagen de perfil
        if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] == 0) {
            $fotoPerfil = $_FILES['fotoPerfil'];
            $nombreArchivo = basename($fotoPerfil['name']);
            $rutaDestino = '../Img/User/' . $nombreArchivo;
            $tipoArchivo = strtolower(pathinfo($rutaDestino, PATHINFO_EXTENSION));

            if (in_array($tipoArchivo, ['jpg', 'jpeg', 'png', 'gif'])) {
                if (move_uploaded_file($fotoPerfil['tmp_name'], $rutaDestino)) {
                    $empleado->setFotoPerfil($rutaDestino); // Establecer la ruta de la foto de perfil

                    // Intentar insertar el empleado y manejar excepciones
                    try {
                        $empleado->insertarEmpleado();
                        header('Location: ../View/Admin/ViewGestionEmp.php');
                        exit; // Asegurarse de salir después de redirigir
                    } catch (mysqli_sql_exception $e) {
                        // Capturar y manejar la excepción
                        echo "Error al insertar el empleado: " . $e->getMessage();
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

    // Modificar empleado
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['accion'] == 'actualizar') {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $empleadoData = obtenerEmpleadoPorID($conn, $id);

            if ($empleadoData) {
                // Crear objeto Empleado con los datos actuales y nuevos del formulario
                $empleado = new Empleado($conn);
                $empleado->setNoEmpleado($id);
                $empleado->setNombre($_POST['nombre'] ?? $empleadoData['nombre']);
                $empleado->setApellido($_POST['apellido'] ?? $empleadoData['apellido']);
                $empleado->setSexo($_POST['sexo'] ?? $empleadoData['sexo']);
                $empleado->setFechaNac($_POST['fechaNac'] ?? $empleadoData['fechaNac']);
                $empleado->setFechaIngreso($_POST['fechaIngreso'] ?? $empleadoData['fechaIngreso']);
                $empleado->setSueldo($_POST['sueldo'] ?? $empleadoData['sueldo']);
                $empleado->setCargo($_POST['cargo'] ?? $empleadoData['cargo']);
                $empleado->setTelefono($_POST['telefono'] ?? $empleadoData['telefono']);
                $empleado->setDireccion($_POST['direccion'] ?? $empleadoData['direccion']);
                $empleado->setFotoPerfil($empleadoData['urlFotoPerfil']); // Mantener imagen actual

                // Manejo de nueva imagen de perfil
                if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] == 0) {
                    $fotoPerfil = $_FILES['fotoPerfil'];
                    $nombreArchivo = basename($fotoPerfil['name']);
                    $rutaDestino = '../Img/User/' . $nombreArchivo;
                    $tipoArchivo = strtolower(pathinfo($rutaDestino, PATHINFO_EXTENSION));
                    
                    if (in_array($tipoArchivo, ['jpg', 'jpeg', 'png', 'gif']) && move_uploaded_file($fotoPerfil['tmp_name'], $rutaDestino)) {
                        $empleado->setFotoPerfil($rutaDestino); // Actualizar con nueva imagen
                    } else {
                        echo "Error al subir la nueva imagen.";
                        exit;
                    }
                }

                // Ejecutar la actualización en base de datos
                $respuesta = $empleado->modificarEmpleado(); // Llamar al método de modificación
                if ($respuesta) {
                    header('Location: ../View/Admin/ViewGestionEmp.php');
                    exit; // Asegurarse de salir después de redirigir
                }
            } else {
                echo "No se encontró el empleado especificado.";
            }
        } else {
            echo "ID de empleado no especificado.";
        }
    }

    // Eliminar empleado
    if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar') {
        $id = $_GET['id'];
        $empleado = new Empleado($conn);
        $resultado = $empleado->eliminarEmpleado($id); // Llamar al método para eliminar empleado
        if ($resultado) {
            header('Location: ../View/Admin/ViewGestionEmp.php');
            exit; // Asegurarse de salir después de redirigir
        } else {
            echo "Error al eliminar empleado: " . $conn->error;
        }
    }

?>
