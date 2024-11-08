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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['accion'] == 'crear') {
    $Recepcion = new Recepcion($conn);
    $Recepcion->setCantidadProducto($_POST['cantidadProducto']);
    $Recepcion->setFecha($_POST['fecha']);
    $Recepcion->setComentario($_POST['comentario']);
    $Recepcion->setIdProveedor($_POST['idProveedor']);
    $Recepcion->setFolio($_POST['folio']);

    try {
        $Recepcion->insertarRecepcion();
        header('Location: ../View/Admin/ViewGestionRecep.php');
        exit;
    } catch (mysqli_sql_exception $e) {
        echo "Error al insertar la recepción: " . $e->getMessage();
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
                header('Location: ../View/Admin/ViewGestionRecep.php');
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
    $id = $_GET['idRep'];
    $Recepcion = new Recepcion($conn);

    if ($Recepcion->eliminarRecepcion($id)) {
        header('Location: ../View/Admin/ViewGestionRecep.php');
        exit;
    } else {
        echo "Error al eliminar la recepción: " . $conn->error;
    }
}
?>
