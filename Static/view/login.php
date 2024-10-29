<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <meta name="description" content="Inicio de sesión">
    <!-- Preload y estilos -->
    <link rel="preload" href="../Css/Login.css" as="style">
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
                    <h2>Iniciar Sesión</h2>
                    
                    <form method="POST" name="frm1" id="frm1" action="../Controller/Autentificacion.php" onsubmit="return validacionc();">
                        <label for="usuario">Usuario</label>
                        <input type="text" id="usuario" name="usuario" placeholder="Ingresa tu usuario" required>
                        <label for="contrasena">Contraseña</label>
                        <input type="password" id="contrasena" name="contrasena" placeholder="*********" required>
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
