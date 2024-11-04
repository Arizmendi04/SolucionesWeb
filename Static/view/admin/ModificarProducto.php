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
</head>
<body>
    <!-- Formulario de modificación de Producto -->
    <div class="formContainer">
        <div class="formModificar">
            <h2>Modificar Producto</h2>

            <?php if ($Producto): ?>
                <form action="../../Controller/Productos.php?accion=actualizar" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $Producto['noProducto']; ?>">

                    <!-- Campos no editables (readonly) -->
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo $Producto['nombre']; ?>" readonly>

                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" value="<?php echo $Producto['apellido']; ?>" readonly>

                    <label for="sexo">Sexo:</label>
                    <select id="sexo" name="sexo" disabled>
                        <option value="Masculino" <?php echo $Producto['sexo'] == 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                        <option value="Femenino" <?php echo $Producto['sexo'] == 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
                        <option value="Otro" <?php echo $Producto['sexo'] == 'Otro' ? 'selected' : ''; ?>>Otro</option>
                    </select>

                    <label for="fechaNac">Fecha de Nacimiento:</label>
                    <input type="date" id="fechaNac" name="fechaNac" value="<?php echo $Producto['fechaNac']; ?>" readonly>

                    <label for="fechaIngreso">Fecha de Ingreso:</label>
                    <input type="date" id="fechaIngreso" name="fechaIngreso" value="<?php echo $Producto['fechaIngreso']; ?>" readonly>

                    <!-- Campos editables -->
                    <label for="sueldo">Sueldo:</label>
                    <input type="number" step="0.01" id="sueldo" name="sueldo" value="<?php echo $Producto['sueldo']; ?>" required>

                    <label for="cargo">Cargo:</label>
                    <input type="text" id="cargo" name="cargo" value="<?php echo $Producto['cargo']; ?>" required>

                    <label for="telefono">Teléfono:</label>
                    <input type="number" id="telefono" name="telefono" value="<?php echo $Producto['telefono']; ?>" required>

                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" value="<?php echo $Producto['direccion']; ?>" required>

                    <label for="fotoPerfil">Foto de Perfil:</label>
                    <input type="file" id="fotoPerfil" name="fotoPerfil" accept="image/*">

                    <button type="submit" name="accion" value="actualizar">Actualizar</button>

                    <br><br>

                </form>

                <!-- Botón de Cancelar -->
                <button type="button" onclick="location.href='/SolucionesWeb/Static/View/Admin/ViewGestionEmp.php'" class="btn-secundario">Cancelar</button>
                
            <?php else: ?>
                <p>No se encontró el Producto especificado.</p>
            <?php endif; ?>
        </div>
    </div>
 
</body>
</html>
