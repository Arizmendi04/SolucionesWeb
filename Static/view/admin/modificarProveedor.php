<?php 

    include 'HeaderA.php';
    include '../../Controller/Proveedores.php'; 
    include '../../Controller/Connect/Db.php';
    include '../../Controller/Sesion.php';

    $proveedorId = isset($_GET['id']) ? $_GET['id'] : null;
    $proveedor = null;

    if ($proveedorId) {
        $proveedor = obtenerProveedorPorId($conn, $proveedorId); // Función para obtener el proveedor por ID
    }

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Proveedor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../Css/Crud.css">
</head>
<body>
    <!-- Formulario de modificación de proveedor -->
    <div class="formContainer">
        <div class="formModificar">
            <h2>Modificar Proveedor</h2>

            <?php if ($proveedor): ?>
                <form action="../../Controller/Proveedores.php?accion=actualizar" method="POST">
                    <input type="hidden" name="id" value="<?php echo $proveedor['idProveedor']; ?>">

                    <label for="razonSocial">Razón Social:</label>
                    <input type="text" id="razonSocial" name="razonSocial" value="<?php echo $proveedor['razonSocial']; ?>" required>

                    <label for="nombreComercial">Nombre Comercial:</label>
                    <input type="text" id="nombreComercial" name="nombreComercial" value="<?php echo $proveedor['nombreComercial']; ?>" required>

                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" value="<?php echo $proveedor['telefono']; ?>" required>
                    <p class="alert alert-danger" id="errorTelefono" style="display:none;">El teléfono debe de tener 10 dígitos</p>

                    <label for="correo">Correo:</label>
                    <input type="email" id="correo" name="correo" value="<?php echo $proveedor['correo']; ?>" required>

                    <button type="submit" name="accion" value="actualizar" onclick="return validacionProveedor();">Actualizar</button>

                    <br><br>
                </form>

                <!-- Botón de Cancelar -->
                <button type="button" onclick="location.href='/SolucionesWeb/Static/View/Admin/ViewGestionProve.php'" class="btn-secundario">Cancelar</button>
                
            <?php else: ?>
                <p>No se encontró el proveedor especificado.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="../../Controller/Js/Validaciones.js"></script>
 
</body>
</html>
