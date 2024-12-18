<?php 
    include 'HeaderA.php';
    include '../../Controller/Empleados.php';
    include '../../Controller/Connect/Db.php';
    include '../../Controller/Sesion.php';

    $empleadoId = isset($_GET['id']) ? $_GET['id'] : null;
    $empleado = null;

    if ($empleadoId) {
        $empleado = obtenerEmpleadoPorId($conn, $empleadoId); 
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CRUD de Empleados</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Viga&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../../Css/modificar.css">
    </head>
    <body>
        <!-- Formulario de modificación de empleado -->
        <div class="formContainer">
            <div class="formModificar">
                <h2>Modificar Empleado</h2>

                <?php if ($empleado): ?>
                    <form action="../../Controller/Empleados.php?accion=actualizar" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $empleado['noEmpleado']; ?>">

                        <!-- Campos no editables (readonly) -->
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo $empleado['nombre']; ?>" readonly>

                        <label for="apellido">Apellido:</label>
                        <input type="text" id="apellido" name="apellido" value="<?php echo $empleado['apellido']; ?>" readonly>

                        <label for="sexo">Sexo:</label>
                        <select id="sexo" name="sexo" disabled>
                            <option value="Masculino" <?php echo $empleado['sexo'] == 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                            <option value="Femenino" <?php echo $empleado['sexo'] == 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
                            <option value="Otro" <?php echo $empleado['sexo'] == 'Otro' ? 'selected' : ''; ?>>Otro</option>
                        </select>

                        <label for="fechaNac">Fecha de Nacimiento:</label>
                        <input type="date" id="fechaNac" name="fechaNac" value="<?php echo $empleado['fechaNac']; ?>" readonly class="form-control custom-date">

                        <label for="fechaIngreso">Fecha de Ingreso:</label>
                        <input type="date" id="fechaIngreso" name="fechaIngreso" value="<?php echo $empleado['fechaIngreso']; ?>" readonly class="form-control custom-date">

                        <!-- Campos editables -->
                        <label for="sueldo">Sueldo:</label>
                        <input type="number" id="sueldo" name="sueldo" value="<?php echo $empleado['sueldo']; ?>" required>
                        <p class="alert alert-danger" id="errorSueldo" style="display:none;">Por favor ingresa un sueldo válido.</p>

                        <label for="cargo">Cargo:</label>
                        <input type="text" id="cargo" name="cargo" value="<?php echo $empleado['cargo']; ?>" required>

                        <label for="telefono">Teléfono:</label>
                        <input type="number" id="telefono" name="telefono" value="<?php echo $empleado['telefono']; ?>" required>
                        <p class="alert alert-danger" id="errorTelefono" style="display:none;">Por favor ingresa un teléfono válido.</p>

                        <label for="direccion">Dirección:</label>
                        <input type="text" id="direccion" name="direccion" value="<?php echo $empleado['direccion']; ?>" required>

                        <button type="submit" name="accion" class="btn-primario" value="actualizar" onclick="return validacionModiEmpleado();">Actualizar</button>

                    </form>

                    <!-- Botón de Cancelar -->
                    <button type="button" onclick="location.href='/SolucionesWeb/Static/View/Admin/ViewGestionEmp.php'" class="btn-secundario">Cancelar</button>
                    
                <?php else: ?>
                    <p>No se encontró el empleado especificado.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <script src="../../Controller/Js/Validaciones.js"></script>
    </body>
</html>
