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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Viga&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../Css/Crud.css">
    
</head>
<body>
    <div class="container">
        <h1>Gestión de Empleados</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $_SESSION['error']; ?>
                <?php unset($_SESSION['error']); // Eliminar el mensaje después de mostrarlo ?>
            </div>
        <?php endif; ?>

        <div class="layout">
            <!-- Formulario de registro de empleado -->
            <div class="formulario">
                <h2>Registrar Empleado</h2>
                <form action="../../Controller/Empleados.php" method="POST" enctype="multipart/form-data">
                    <label for="name">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>
                    <p class="alert alert-danger" id="errorNombre" style="display:none;">
                        Ingresa un nombre válido, por favor.
                    </p>

                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" required>
                    <p class="alert alert-danger" id="errorApellido" style="display:none;">
                        Ingresa un apellido válido, por favor.
                    </p>

                    <label for="sexo">Sexo:</label>
                    <select id="sexo" name="sexo" required>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                        <option value="Otro">Otro</option>
                    </select>

                    <label for="fechaNac">Fecha de Nacimiento:</label>
                    <input type="date" id="fechaNac" name="fechaNac" required>
                    <p class="alert alert-danger" id="errorFechaNac" style="display:none;">Por favor ingresa una fecha de nacimiento válida.</p>

                    <label for="fechaIngreso">Fecha de Ingreso:</label>
                    <input type="date" id="fechaIngreso" name="fechaIngreso" required>
                    <p class="alert alert-danger" id="errorFechaIngreso" style="display:none;">Por favor ingresa una fecha de ingreso válida.</p>

                    <label for="sueldo">Sueldo:</label>
                    <input type="number" id="sueldo" name="sueldo" required>
                    <p class="alert alert-danger" id="errorSueldo" style="display:none;">Por favor ingresa un sueldo válido.</p>

                    <label for="cargo">Cargo:</label>
                    <input type="text" id="cargo" name="cargo" required>

                    <label for="telefono">Teléfono:</label>
                    <input type="number" id="telefono" name="telefono" required>
                    <p class="alert alert-danger" id="errorTelefono" style="display:none;">Por favor ingresa un teléfono válido.</p>

                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" required>

                    <label for="fotoPerfil">Foto de Perfil:</label>
                    <input type="file" id="fotoPerfil" name="fotoPerfil" accept="image/*" required>

                    <button type="submit" name="accion" value="crear" onclick="return validacionEmpleado();">Registrar</button>
                </form>
            </div>

            <!-- Tabla para consultar empleados -->
            <div class="tabla">
                <!-- Barra de búsqueda de empleados -->
                <div class="busqueda">
                    <h2>Lista de Empleados</h2>
                    <input type="text" id="busqueda" placeholder="Buscar por nombre o apellido" oninput="filtrarEmpleados(this.value)">
                </div>

                <table id="tablaEmpleados">
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
                        $empleados = solicitarEmpleados($conn);
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="../../Controller/Js/ConfirmElim.js"></script>
    <script src="../../Controller/Js/Validaciones.js"></script>

    <script>
        function filtrarEmpleados(query) {
            const tabla = document.getElementById("tablaEmpleados");
            const filas = tabla.getElementsByTagName("tr");

            for (let i = 1; i < filas.length; i++) {
                const celdas = filas[i].getElementsByTagName("td");
                let encontrado = false;

                // Solo verificar las celdas de Nombre y Apellido
                const nombre = celdas[1].innerText.toLowerCase(); // Celda de Nombre
                const apellido = celdas[2].innerText.toLowerCase(); // Celda de Apellido
                
                // Verificar si el query coincide con el nombre o apellido
                if (nombre.includes(query.toLowerCase()) || apellido.includes(query.toLowerCase())) {
                    encontrado = true;
                }
                
                filas[i].style.display = encontrado ? "" : "none";
            }
        }
    </script>
        
</body>
</html>
