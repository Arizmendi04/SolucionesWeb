<?php 
    include 'HeaderA.php';
    include '../../Controller/Recepciones.php';
    include '../../Controller/Connect/Db.php';
    include '../../Controller/Sesion.php';

    $RecepcionId = isset($_GET['id']) ? $_GET['id'] : null;
    $Recepcion = null;

    if ($RecepcionId) {
        $Recepcion = obtenerRecepcionPorId($conn, $RecepcionId);
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Recepción</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Viga&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../Css/modificar.css">
</head>
<body>
    <div class="formContainer">
        <div class="formModificar">
            <h2>Modificar Recepción</h2>

            <?php if ($Recepcion): ?>
                <form action="../../Controller/Recepciones.php?accion=editar" method="POST">
                    <input type="hidden" name="id" value="<?php echo $Recepcion['idRep']; ?>">

                    <!-- Campo cantidadProducto bloqueado -->
                    <label for="cantidadProducto">Cantidad de Producto:</label>
                    <input type="text" id="cantidadProducto" value="<?php echo $Recepcion['cantidadProducto']; ?>" disabled>


                    <!-- Campo folio bloqueado -->
                    <label for="folio">Folio Producto:</label>
                    <input type="text" id="folio" value="<?php echo $Recepcion['folio']; ?>" disabled>

                    <!-- Campo proveedor bloqueado -->
                    <label for="proveedor">Proveedor:</label>
                    <input type="text" id="idProveedor" value="<?php echo $Recepcion['idProveedor']; ?>" disabled>

                    <!-- Fecha editable -->
                    <label for="fecha">Fecha de Recepción:</label>
                    <input type="date" id="fecha" name="fecha" value="<?php echo $Recepcion['fecha']; ?>" required>
                    
                    <input type="date" id="fecha" name="fecha" class="form-control" required>
                    <p class="alert alert-danger" id="errorFecha" style="display:none;">Por favor ingresa una fecha con el formato dd/mm/aaaa.</p>

                    <!-- Comentario editable -->
                    <label for="comentario">Comentario:</label>
                    <textarea id="comentario" name="comentario" required class="form-control"><?php echo $Recepcion['comentario']; ?></textarea>

                    <br>

                    <button type="submit" name="accion" class="btn-primario" value="editar" onclick="return validacionModiRecepcion();">Actualizar</button>
                    <br><br>

                </form>

                <button type="button" onclick="location.href='/SolucionesWeb/Static/View/Admin/ViewGestionRec.php'" class="btn-secundario">Cancelar</button>
                
            <?php else: ?>
                <p>No se encontró la Recepción especificada.</p>
            <?php endif; ?>
        </div>
    </div>
                
    <script src="/SolucionesWeb/Static/Controller/Js/Recepciones.js"></script>

</body>
</html>
