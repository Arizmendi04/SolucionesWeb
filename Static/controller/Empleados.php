<?php include 'Connect/Db.php'; ?>
<?php include 'Sesion.php'; ?>

<?php
    function obtenerEmpleados($conn) {
        // Consulta para obtener los empleados
        $sql = "SELECT * FROM empleado";
        $resultado = mysqli_query($conn, $sql);
        $empleados = [];
        while ($fila = $resultado->fetch_assoc()) {
            $empleados[] = $fila;
        }
        return $empleados;
    }

    function obtenerEmpleadoPorID($conn, $idEmpleado) {
        $sql = "SELECT * FROM empleado WHERE noEmpleado = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idEmpleado);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }
    
    // Crear empleado
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['accion'] == 'crear') {
        // Obtener los datos del formulario
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $sexo = $_POST['sexo'];
        $fechaNac = $_POST['fechaNac'];
        $fechaIngreso = $_POST['fechaIngreso'];
        $sueldo = $_POST['sueldo'];
        $cargo = $_POST['cargo'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        
        // Manejar la subida de la imagen de perfil
        if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] == 0) {
            $fotoPerfil = $_FILES['fotoPerfil'];
            $nombreArchivo = basename($fotoPerfil['name']);
            $rutaDestino = '../Img/User' . $nombreArchivo;
            $tipoArchivo = strtolower(pathinfo($rutaDestino, PATHINFO_EXTENSION));

            // Validar el tipo de archivo
            $tiposPermitidos = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($tipoArchivo, $tiposPermitidos)) {
                // Mover el archivo subido a la carpeta 'uploads'
                if (move_uploaded_file($fotoPerfil['tmp_name'], $rutaDestino)) {
                    // Inserción en la base de datos
                    $sql = "INSERT INTO empleado (nombre, apellido, sexo, fechaNac, fechaIngreso, sueldo, cargo, telefono, direccion, urlFotoPerfil) 
                            VALUES ('$nombre', '$apellido', '$sexo', '$fechaNac', '$fechaIngreso', '$sueldo', '$cargo', '$telefono', '$direccion', '$rutaDestino')";

                    if (mysqli_query($conn, $sql)) {
                        header('Location: ../View/Admin/ViewGestionEmp.php');
                    } else {
                        echo "Error al crear empleado: " . mysqli_error($conn);
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
    if (isset($_GET['accion']) && $_GET['accion'] == 'actualizar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        // Verificar si el ID está disponible
        if (isset($_POST['id'])) {
            $id = $_POST['id'];

            // Obtener los datos actuales del empleado
            $sql = "SELECT * FROM empleado WHERE noEmpleado = $id";
            $resultado = mysqli_query($conn, $sql);

            if (mysqli_num_rows($resultado) == 1) {
                // Extraer los datos actuales
                $empleado = mysqli_fetch_assoc($resultado);
                // Asignar los valores a variables
                $nombre = $_POST['nombre'] ?? $empleado['nombre'];
                $apellido = $_POST['apellido'] ?? $empleado['apellido'];
                $sexo = $_POST['sexo'] ?? $empleado['sexo'];
                $fechaNac = $_POST['fechaNac'] ?? $empleado['fechaNac'];
                $fechaIngreso = $_POST['fechaIngreso'] ?? $empleado['fechaIngreso'];
                $sueldo = $_POST['sueldo'] ?? $empleado['sueldo'];
                $cargo = $_POST['cargo'] ?? $empleado['cargo'];
                $telefono = $_POST['telefono'] ?? $empleado['telefono'];
                $direccion = $_POST['direccion'] ?? $empleado['direccion'];
                $rutaDestino = $empleado['urlFotoPerfil']; // Mantener la imagen actual si no se sube una nueva

                // Manejar la subida de la nueva imagen de perfil
                if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] == 0) {
                    $fotoPerfil = $_FILES['fotoPerfil'];
                    $nombreArchivo = basename($fotoPerfil['name']);
                    $rutaDestino = '../Img/User/' . $nombreArchivo;
                    $tipoArchivo = strtolower(pathinfo($rutaDestino, PATHINFO_EXTENSION));

                    $tiposPermitidos = ['jpg', 'jpeg', 'png', 'gif'];
                    if (in_array($tipoArchivo, $tiposPermitidos)) {
                        if (move_uploaded_file($fotoPerfil['tmp_name'], $rutaDestino)) {
                            // Actualizar la imagen de perfil
                            $sql = "UPDATE empleado SET nombre='$nombre', apellido='$apellido', sexo='$sexo', fechaNac='$fechaNac', fechaIngreso='$fechaIngreso', sueldo='$sueldo', cargo='$cargo', telefono='$telefono', direccion='$direccion', urlFotoPerfil='$rutaDestino' WHERE noEmpleado = $id";
                        } else {
                            echo "Error al subir la imagen.";
                            exit;
                        }
                    } else {
                        echo "Solo se permiten archivos con las siguientes extensiones: JPG, JPEG, PNG, GIF.";
                        exit;
                    }
                } else {
                    // Si no se subió una nueva imagen, no se actualiza la columna de la imagen
                    $sql = "UPDATE empleado SET nombre='$nombre', apellido='$apellido', sexo='$sexo', fechaNac='$fechaNac', fechaIngreso='$fechaIngreso', sueldo='$sueldo', cargo='$cargo', telefono='$telefono', direccion='$direccion' WHERE noEmpleado = $id";
                }

                // Ejecutar la consulta
                if (mysqli_query($conn, $sql)) {
                    header('Location: ../View/Admin/ViewGestionEmp.php');
                } else {
                    echo "Error al modificar empleado: " . mysqli_error($conn);
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

        $sql = "DELETE FROM empleado WHERE noEmpleado = $id";
        if ($conn->query($sql)) {
            header('Location: ../View/Admin/ViewGestionEmp.php');
        } else {
            echo "Error al eliminar empleado: " . $conn->error;
        }
    }

?>
