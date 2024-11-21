<?php 
    include 'HeaderA.php';
    include '../../Controller/Recepciones.php';
    include '../../Controller/Connect/Db.php';
    include '../../Controller/Proveedores.php';
    include '../../Controller/Sesion.php';
    include '../../Controller/Productos.php';

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


                        <!-- Campo nombre de producto bloqueado -->
                        <label for="proveedor">Producto:</label>
                        <div class="busqueda mb-3">
                            <?php
                                $producto = obtenerProductoPorID($conn, $Recepcion['folio'] ?? null);
                                $nombreProd = $producto ? (!empty($producto['nombreProd']) ? $producto['nombreProd'] : 'Producto no encontrado') : 'Producto no encontrado';

                            ?>
                            <!-- Campo de texto para mostrar el nombre del producto (solo lectura) -->
                            <input type="text" id="nombre" value="<?php echo htmlspecialchars($nombreProd); ?>" readonly class="form-control">
                            <!-- Campo oculto para enviar el ID del proveedor -->
                            <input type="hidden" id="idProducto" name="idProducto" value="<?php echo htmlspecialchars($Recepcion['folio'] ?? ''); ?>">
                        </div>

                        <label for="proveedor">Proveedor:</label>
                        <div class="busqueda mb-3">
                            <?php
                                $proveedor = obtenerProveedorPorID($conn, $Recepcion['idProveedor'] ?? null);
                                $nombreProveedor = $proveedor ? (!empty($proveedor['nombreComercial']) ? $proveedor['nombreComercial'] : $proveedor['razonSocial']) : 'Proveedor no encontrado';
                            ?>
                            <!-- Campo de texto para mostrar el nombre del proveedor (solo lectura) -->
                            <input type="text" id="proveedorNombre" value="<?php echo htmlspecialchars($nombreProveedor); ?>" readonly class="form-control">
                            <!-- Campo oculto para enviar el ID del proveedor -->
                            <input type="hidden" id="idProveedor" name="idProveedor" value="<?php echo htmlspecialchars($Recepcion['idProveedor'] ?? ''); ?>">
                        </div>

                        <br>

                        <label for="fecha" class="form-label">Fecha de Recepción:</label>
                        <input type="date" id="fecha" name="fecha" value="<?php echo $Recepcion['fecha']; ?>" required class="form-control custom-date">
                        <p class="alert alert-danger" id="errorFecha" style="display:none;">Por favor ingresa una fecha con el formato dd/mm/aaaa.</p>

                        <br><br>

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
