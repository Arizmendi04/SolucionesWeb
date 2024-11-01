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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Viga&display=swap" rel="stylesheet">
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

            <!-- Tabla para consultar empleados -->
            <div class="tabla">
                <h2>Lista de Empleados</h2>
                <table>
                    <thead>
                        <tr>
                            <th>No. Empleado</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Sexo</th>
                            <th>Fecha Nac</th>
                            <th>Fecha Ingreso</th>
                            <th>Sueldo</th>
                            <th>Cargo</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $empleados = obtenerEmpleados($conn);
                        foreach ($empleados as $empleado) {
                            echo "<tr>
                                <td>{$empleado['noEmpleado']}</td>
                                <td>{$empleado['nombre']}</td>
                                <td>{$empleado['apellido']}</td>
                                <td>{$empleado['sexo']}</td>
                                <td>{$empleado['fechaNac']}</td>
                                <td>{$empleado['fechaIngreso']}</td>
                                <td>{$empleado['sueldo']}</td>
                                <td>{$empleado['cargo']}</td>
                                <td>{$empleado['telefono']}</td>
                                <td>{$empleado['direccion']}</td>
                                <td>
                                    <div class='boton-contenedor'>
                                        <a href='/SolucionesWeb/Static/view/admin/modificarEmpleado.php?accion=editar&id={$empleado['noEmpleado']}' class='boton editar'>Editar</a>
                                    <br><br>
                                        <a href='../../Controller/Empleados.php?accion=eliminar&id={$empleado['noEmpleado']}' class='boton eliminar'>Eliminar</a>
                                    </div>
                                </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <h2>Confirmar Eliminación</h2>
            <p>¿Estás seguro de que deseas eliminar este empleado? Esta acción no se puede deshacer.</p>
            <button id="confirmDelete" class="confirm">Confirmar</button>
            <button id="cancelDelete" class="cancel">Cancelar</button>
        </div>
    </div>
    <script src="../../Controller/Js/Empleados.js"></script>
</body>
</html>
