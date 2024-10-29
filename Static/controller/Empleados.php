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
