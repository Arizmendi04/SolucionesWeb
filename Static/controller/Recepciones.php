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

// Crear una nueva recepción
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica si la acción es 'crear' y si los campos necesarios están presentes
    if ($_POST['accion'] == 'crear' && isset($_POST['idProducto'], $_POST['cantidadProducto'], $_POST['fecha'], $_POST['idProveedor'])) {
        $folio = $_POST['idProducto']; // Obtén el folio del producto seleccionado
        $cantidadProducto = $_POST['cantidadProducto'];
        $fecha = $_POST['fecha'];
        $comentario = isset($_POST['comentario']) ? $_POST['comentario'] : ''; // Comentario opcional
        $idProveedor = $_POST['idProveedor'];

        // Crear la instancia de la clase Recepcion y pasar los valores
        $Recepcion = new Recepcion($conn);
        $Recepcion->setCantidadProducto($cantidadProducto);
        $Recepcion->setFecha($fecha);
        $Recepcion->setComentario($comentario);
        $Recepcion->setIdProveedor($idProveedor);
        $Recepcion->setFolio($folio); // Asegúrate de que el folio sea correcto

        try {
            // Insertar la recepción en la base de datos
            $Recepcion->insertarRecepcion();
            header('Location: ../View/Admin/ViewGestionRec.php');
            exit;
        } catch (mysqli_sql_exception $e) {
            echo "Error al insertar la recepción: " . $e->getMessage();
        }
    }
}
// Modificar una recepción existente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['accion'] == 'actualizar') {
    if (isset($_POST['idRep'])) {
        $id = $_POST['idRep'];
        $RecepcionData = obtenerRecepcionPorID($conn, $id);

        if ($RecepcionData) {
            $Recepcion = new Recepcion($conn);
            $Recepcion->setIdRep($id);
            $Recepcion->setCantidadProducto($_POST['cantidadProducto'] ?? $RecepcionData['cantidadProducto']);
            $Recepcion->setFecha($_POST['fecha'] ?? $RecepcionData['fecha']);
            $Recepcion->setComentario($_POST['comentario'] ?? $RecepcionData['comentario']);
            $Recepcion->setIdProveedor($_POST['idProveedor'] ?? $RecepcionData['idProveedor']);
            $Recepcion->setFolio($_POST['folio'] ?? $RecepcionData['folio']);

            if ($Recepcion->modificarRecepcion()) {
                header('Location: ../View/Admin/ViewGestionRec.php');
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
