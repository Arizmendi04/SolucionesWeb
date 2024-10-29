<?php include 'HeaderA.php'?>
<?php include '../../Controller/Empleados.php'; ?>
<?php include '../../Controller/Connect/Db.php'; ?>
<?php include '../../Controller/Sesion.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Empleados</title>
    <link rel="stylesheet" href="../../Css/Empleados.css">
</head>
<body>
    <div class="container">
        <h1>Gestión de Empleados</h1>

        <div class="layout">
            <!-- Formulario de registro de empleado -->
            <div class="formulario">
                <h2>Registrar Empleado</h2>
                <form action="../../Controller/Empleados.php" method="POST" enctype="multipart/form-data">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>

                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" required>

                    <label for="sexo">Sexo:</label>
                    <select id="sexo" name="sexo" required>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                        <option value="Otro">Otro</option>
                    </select>

                    <label for="fechaNac">Fecha de Nacimiento:</label>
                    <input type="date" id="fechaNac" name="fechaNac" required>

                    <label for="fechaIngreso">Fecha de Ingreso:</label>
                    <input type="date" id="fechaIngreso" name="fechaIngreso" required>

                    <label for="sueldo">Sueldo:</label>
                    <input type="number" step="0.01" id="sueldo" name="sueldo" required>

                    <label for="cargo">Cargo:</label>
                    <input type="text" id="cargo" name="cargo" required>

                    <label for="telefono">Teléfono:</label>
                    <input type="number" id="telefono" name="telefono" required>

                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" required>

                    <label for="fotoPerfil">Foto de Perfil:</label>
                    <input type="file" id="fotoPerfil" name="fotoPerfil" accept="image/*" required>

                    <button type="submit" name="accion" value="crear">Registrar</button>
                </form>
            </div>
    </div>