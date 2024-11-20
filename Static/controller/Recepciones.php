<?php
include 'Connect/Db.php';
include 'Sesion.php';
include($_SERVER['DOCUMENT_ROOT'].'/SolucionesWeb/Static/Model/Recepcion.php');

// Solicitar todas las recepciones
function solicitarRecepciones($conn) {
    $Recepcion = new Recepcion($conn);
    return $Recepcion->obtenerRecepciones();
}

// Filtrar recepciones según un parámetro
function filtrarRecepciones($conn, $parametro) {
    $Recepcion = new Recepcion($conn);
    return $Recepcion->filtrarRecepcion($parametro);
}

// Obtener una recepción específica por ID
function obtenerRecepcionPorID($conn, $idRecepcion) {
    $Recepcion = new Recepcion($conn);
    return $Recepcion->obtenerRecepcion($idRecepcion);
}

// Creamos una nueva recepción
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificamos si la acción es 'crear' y si los campos necesarios están presentes
    if ($_POST['accion'] == 'crear' && isset($_POST['idProducto'], $_POST['cantidadProducto'], $_POST['fecha'], $_POST['idProveedor'])) {
        $folio = $_POST['idProducto']; // Obtén el folio del producto seleccionado
        $cantidadProducto = $_POST['cantidadProducto'];
        $fecha = $_POST['fecha'];
        $comentario = isset($_POST['comentario']) ? $_POST['comentario'] : ''; // Comentario opcional
        $idProveedor = $_POST['idProveedor'];

        // Creamos la instancia de la clase Recepcion y pasar los valores
        $Recepcion = new Recepcion($conn);
        $Recepcion->setCantidadProducto($cantidadProducto);
        $Recepcion->setFecha($fecha);
        $Recepcion->setComentario($comentario);
        $Recepcion->setIdProveedor($idProveedor);
        $Recepcion->setFolio($folio); 

        try {
            // Insertamos la recepción en la base de datos
            $Recepcion->insertarRecepcion();
            header('Location: ../View/Admin/ViewGestionRec.php');
            exit;
        } catch (mysqli_sql_exception $e) {
            echo "Error al insertar la recepción: " . $e->getMessage();
        }
    }
}
    // Modificamos una recepción existente
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['accion'] == 'editar') {
        if (isset($_POST['id'])) {  // Verificamos que el ID de recepción esté presente
            $idRep = $_POST['id'];  // Obtenemos el ID de recepción desde el formulario

            // Creamos una instancia de Recepcion para obtener los datos actuales
            $recepcionData = obtenerRecepcionPorID($conn, $idRep);  // Obtenemos datos actuales de la recepción
        
            if ($recepcionData) {
                $recepcion = new Recepcion($conn);
                $recepcion->setIdRep($idRep);  // Establecemos el ID de la recepción
                $recepcion->setCantidadProducto($_POST['cantidadProducto'] ?? $recepcionData['cantidadProducto']);
                $recepcion->setFecha($_POST['fecha'] ?? $recepcionData['fecha']);
                $recepcion->setComentario($_POST['comentario'] ?? $recepcionData['comentario']);
                $recepcion->setIdProveedor($_POST['idProveedor'] ?? $recepcionData['idProveedor']);
                $recepcion->setFolio($_POST['folio'] ?? $recepcionData['folio']);
        
                // Ejecutamos la actualización en la base de datos
                $respuesta = $recepcion->modificarRecepcion();  // Llamamos al método de modificación
                if ($respuesta) {
                    header('Location: /SolucionesWeb/Static/View/Admin/ViewGestionRec.php');
                    exit;
                } else {
                    echo "Error al actualizar la recepción.";
                }
            } else {
                echo "No se encontró la recepción especificada.";
            }
        } else {
            echo "ID de recepción no especificado.";
        }
    }

    


    // Eliminar una recepción
    if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar') {
        $idRep = $_GET['id'];
        $recepcion = new Recepcion($conn);

        $resultado = $recepcion->eliminarRecepcion($idRep);
        if ($resultado) {
            header('Location: /SolucionesWeb/Static/View/Admin/ViewGestionRec.php');
            exit;
        } else {
            echo "Error al eliminar la recepción: " . $conn->error;
        }
    }
?>
