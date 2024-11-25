<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Iniciar sesión</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <meta name="description" content="Inicio de sesión">
        <link rel="stylesheet" href="../Css/Login.css">
        
    </head>

    <body>
        <header class="header">
            <div class="contenedor header-content">
                <!-- Sección izquierda con logo y texto -->
                <div class="izquierda">
                    <a class="logo" href="../../index.php">
                        <div class="logo-container">
                            <img class="logo-img" src="../Img/logosinletras.png" alt="Logo">
                            <h1 class="logo_nombre">Soluciones para Plagas</h1>
                        </div>
                    </a>
                </div>
                <!-- Sección derecha con formulario de inicio de sesión -->
                <div class="derecha">
                    <div class="login-box">
                        <h2><b>Iniciar Sesión</h2></b>
                        
                        <!-- Mostrar mensaje de error si existe -->
                        <?php
                            session_start();
                            if (isset($_SESSION["mensaje_error"])) {
                                echo "<div class='alert alert-danger text-center' role='alert'>";
                                echo $_SESSION["mensaje_error"];
                                echo "</div>";
                                unset($_SESSION["mensaje_error"]); // Borra el mensaje después de mostrarlo
                            }
                        ?>
                        <form method="POST" name="frm1" id="frm1" action="../Controller/Autentificacion.php" onsubmit="return validacionc();">
                            <label for="usuario">Usuario</label>
                            <input type="text" id="usuario" name="usuario" placeholder="Ingresa tu usuario" >
                            <p class="alert alert-danger" id="errorUsuario" style="display:none;" >
                                Ingresa un usuario, por favor.
                            </p>

                            <label for="contrasena">Contraseña</label>
                            <input type="password" id="contrasena" name="contrasena" placeholder="*********" >

                            <p class="alert alert-danger" id="errorContra" style="display:none;">
                                Ingresa una contraseña, por favor.
                            </p>
                            <button type="submit" class="boton_primario">Acceder</button>
                        </form>

                    </div>
                </div>
            </div>
        </header>

        <!-- Enlazar el archivo Login.js -->
        <script src="../Controller/Js/Login.js"></script>

    </body>
</html>
