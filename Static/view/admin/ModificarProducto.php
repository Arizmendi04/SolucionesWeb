<?php 

    include 'HeaderA.php';
    include '../../Controller/Productos.php';
    include '../../Controller/Connect/Db.php';
    include '../../Controller/Sesion.php';

    $ProductoId = isset($_GET['id']) ? $_GET['id'] : null;
    $Producto = null;

    if ($ProductoId) {
        $Producto = obtenerProductoPorId($conn, $ProductoId); 
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Productos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Viga&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../Css/Crud.css">
    <link rel="stylesheet" href="../../Css/Productos.css">
</head>
<body>
    <div class="formContainer">
        <div class="formModificar">
            <h2>Modificar Producto</h2>

            <?php if ($Producto): ?>
                <form action="../../Controller/Productos.php?accion=actualizar" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($Producto['folio'] ?? ''); ?>">

                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($Producto['nombreProd'] ?? ''); ?>" required>

                    <label for="categoria">Categoría:</label>
                    <input type="text" id="categoria" name="categoria" value="<?php echo htmlspecialchars($Producto['tipo'] ?? ''); ?>" required>

                    <label for="peso">Peso:</label>
                    <input type="number" step="0.01" id="peso" name="peso" value="<?php echo htmlspecialchars($Producto['peso'] ?? ''); ?>" required>

                    <label for="unidadM">Unidad:</label>
                    <input type="text" id="unidadM" name="unidadM" value="<?php echo htmlspecialchars($Producto['unidadM'] ?? ''); ?>" required>

                    <label for="precio">Precio:</label>
                    <input type="number" step="0.01" id="precio" name="precio" value="<?php echo htmlspecialchars($Producto['precio'] ?? ''); ?>" required>

                    <label for="existencias">Existencias:</label>
                    <input type="number" id="existencia" name="existencia" value="<?php echo htmlspecialchars($Producto['existencia'] ?? ''); ?>" required>



                    <label for="proveedor">Proveedor:</label>
                    <div class="busqueda mb-3">
                        <input type="text" id="proveedorNombre" value="<?php echo htmlspecialchars($Producto['proveedorNombre'] ?? ''); ?>" required placeholder="Busca un proveedor" class="form-control" autocomplete="off" oninput="buscarProveedor(this.value)">
                        <input type="hidden" id="idProveedor" name="idProveedor">
                    </div>
                    <div id="listaProveedores" class="list-group" style="display: none;">
                        <?php
                        foreach ($proveedores as $proveedor) {
                            echo "<a href='javascript:void(0)' onclick='seleccionarProveedor({$proveedor['id']}, \"{$proveedor['nombre']}\")' class='list-group-item list-group-item-action'>{$proveedor['nombre']}</a>";
                        }
                        ?>
                    </div> <!-- Contenedor de la lista de proveedores -->

                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" required><?php echo htmlspecialchars($Producto['descripcion'] ?? ''); ?></textarea>

                    <label for="fotoProducto">Foto del Producto:</label>
                    <input type="file" id="fotoProducto" name="fotoProducto" accept="image/*">

                    <button type="submit" name="accion" value="actualizar">Actualizar</button>
                    <br><br>
                </form>

                <button type="button" onclick="location.href='/SolucionesWeb/Static/View/Admin/ViewGestionEmp.php'" class="btn-secundario">Cancelar</button>
                
            <?php else: ?>
                <p>No se encontró el Producto especificado.</p>
            <?php endif; ?>
        </div>
    </div>
 
</body>
</html>
