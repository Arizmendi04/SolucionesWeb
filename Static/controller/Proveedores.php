<?php 
    include 'Connect/Db.php'; 
    include 'Sesion.php'; 
    include($_SERVER['DOCUMENT_ROOT'].'/SolucionesWeb/Static/Model/Proveedor.php'); 

    function solicitarProveedores($conn) {
        $proveedor = new Proveedor($conn);
        $resultado = $proveedor->obtenerProveedores();
        return $resultado;
    }

    function filtrarProveedores($conn, $parametro) {
        $proveedor = new Proveedor($conn);
        $resultado = $proveedor->filtrarProveedor($parametro);
        return $resultado;
    }

    function obtenerProveedorPorID($conn, $idProveedor) {
        $proveedor = new Proveedor($conn);
        return $proveedor->obtenerProveedor($idProveedor);
    }

    // Crear proveedor
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['accion'] == 'crear') {
        // Obtener los datos del formulario
        $proveedor = new Proveedor($conn);
        $proveedor->setRazonSocial($_POST['razonSocial']);
        $proveedor->setNombreComercial($_POST['nombreComercial']);
        $proveedor->setTelefono($_POST['telefono']);
        $proveedor->setCorreo($_POST['correo']);
        
        // Intentar insertar el proveedor y manejar excepciones
        try {
            $proveedor->insertarProveedor();
            header('Location: ../View/Admin/ViewGestionProve.php');
            exit; // Asegurarse de salir después de redirigir
        } catch (mysqli_sql_exception $e) {
            // Capturar y manejar la excepción
            echo "Error al insertar el proveedor: " . $e->getMessage();
        }
    }

    // Modificar proveedor
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['accion'] == 'actualizar') {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $proveedorData = obtenerProveedorPorID($conn, $id);

            if ($proveedorData) {
                // Crear objeto Proveedor con los datos actuales y nuevos del formulario
                $proveedor = new Proveedor($conn);
                $proveedor->setIdProveedor($id);
                $proveedor->setRazonSocial($_POST['razonSocial'] ?? $proveedorData['razonSocial']);
                $proveedor->setNombreComercial($_POST['nombreComercial'] ?? $proveedorData['nombreComercial']);
                $proveedor->setTelefono($_POST['telefono'] ?? $proveedorData['telefono']);
                $proveedor->setCorreo($_POST['correo'] ?? $proveedorData['correo']);

                // Ejecutar la actualización en base de datos
                $respuesta = $proveedor->modificarProveedor(); // Llamar al método de modificación
                if ($respuesta) {
                    header('Location: ../View/Admin/ViewGestionProve.php');
                    exit; // Asegurarse de salir después de redirigir
                }
            } else {
                echo "No se encontró el proveedor especificado.";
            }
        } else {
            echo "ID de proveedor no especificado.";
        }
    }

    // Eliminar proveedor
    if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar') {
        $id = $_GET['id'];
        $proveedor = new Proveedor($conn);
        $resultado = $proveedor->eliminarProveedor($id); // Llamar al método para eliminar proveedor
        if ($resultado) {
            header('Location: ../View/Admin/ViewGestionProve.php');
            exit; // Asegurarse de salir después de redirigir
        } else {
            echo "Error al eliminar proveedor: " . $conn->error;
        }
    }

    // Buscar proveedores
    if (isset($_GET['accion']) && $_GET['accion'] == 'filtrar') {
        $nombreProveedor = isset($_GET['nombre']) ? $_GET['nombre'] : '';
        // Proteger contra inyecciones SQL
        $nombreProveedor = $conn->real_escape_string($nombreProveedor);
        // Llamar a la función de filtrado
        $resultado = filtrarProveedores($conn, $nombreProveedor);
        // Generar la respuesta en HTML
        if ($resultado && count($resultado) > 0) {
            foreach ($resultado as $proveedor) {
                // Muestra el proveedor en el formato "nombreComercial -> razonSocial"
                echo "<div class='list-group-item' onclick='seleccionarProveedor(\"{$proveedor['idProveedor']}\", \"{$proveedor['nombreComercial']}\")'>{$proveedor['nombreComercial']} -> {$proveedor['razonSocial']}</div>";
            }
        } else {
            echo "<div>No se encontraron proveedores.</div>";
        }
    }
    
?>
