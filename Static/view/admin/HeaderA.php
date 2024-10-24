<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soluciones Para Plagas</title>
    <link rel="stylesheet" href="../../Css/Dashboard.css">
</head>
<body>
    <div class="sidebar">
        <div class="user-profile">
            <img src="user-placeholder.png" alt="Admin" class="user-image">
            <p class="username">Admin</p>
        </div>
        <nav>
            <ul>
                <li>
                    <div class="menu-item">
                        <img src="../../Img/dash.png" class="small-image" alt="Dashboard">
                        <a href="#">Dashboard</a>
                    </div>
                </li>
                <li>
                    <div class="menu-item">
                        <img src="../../Img/ventas.png" class="small-image" alt="Ventas">
                        <a href="#">Ventas</a>
                    </div>
                </li>
                <li>
                    <div class="menu-item">
                        <img src="../../Img/productos.png" class="small-image" alt="Productos">
                        <a href="#">Productos</a>
                    </div>
                </li>
                <li>
                    <div class="menu-item">
                        <img src="../../Img/proveedor.png" class="small-image" alt="Proveedores">
                        <a href="#">Proveedores</a>
                    </div>
                </li>
                <li>
                    <div class="menu-item">
                        <img src="../../Img/empleado.png" class="small-image" alt="Empleados">
                        <a href="#">Empleados</a>
                    </div>
                </li>
                <li>
                    <div class="menu-item">
                        <img src="../../Img/cliente.png" class="small-image" alt="Clientes">
                        <a href="#">Clientes</a>
                    </div>
                </li>
                <li>
                    <div class="menu-item">
                        <img src="../../Img/bd.png" class="small-image" alt="Base de datos">
                        <a href="#">Base de datos</a>
                    </div>
                </li>
                <li>
                    <div class="menu-item">
                        <img src="../../Img/logout.png" class="small-image" alt="Cerrar sesión">
                        <a href="#" id="logoutButton">Cerrar sesión</a>
                    </div>
                </li>
            </ul>
        </nav>        
    </div>

    <div class="alert-overlay" id="alertOverlay"></div>
    <div class="alert-box" id="alertBox">
        <p id="alertMessage">¿Estás seguro de que quieres cerrar sesión?</p>
        <button class="alert-button" onclick="confirmLogout()">Cerrar sesión</button>
        <button class="alert-button" onclick="closeAlert()">Cancelar</button>
    </div>

    <script src="../../Controller/Js/Logout.js"></script>
</body>
</html>
