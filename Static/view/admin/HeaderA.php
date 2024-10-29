<?php include '../../Controller/Sesion.php'; ?>

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
                        <img src="/Soluciones/Static/Img/dash.png" class="small-image" alt="Dashboard">
                        <a href="/Soluciones/Static/View/Admin/DashboardAdmin.php">Dashboard</a>
                    </div>
                </li>
                <li>
                    <div class="menu-item">
                        <img src="/Soluciones/Static/Img/ventas.png" class="small-image" alt="Ventas">
                        <a href="#">Ventas</a>
                    </div>
                </li>
                <li>
                    <div class="menu-item">
                        <img src="/Soluciones/Static/Img/productos.png" class="small-image" alt="Productos">
                        <a href="#">Productos</a>
                    </div>
                </li>
                <li>
                    <div class="menu-item">
                        <img src="/Soluciones/Static/Img/proveedor.png" class="small-image" alt="Proveedores">
                        <a href="#">Proveedores</a>
                    </div>
                </li>
                <li>
                    <div class="menu-item">
                        <img src="/Soluciones/Static/Img/empleado.png" class="small-image" alt="Empleados">
                        <a href="/Soluciones/Static/View/Admin/ViewGestionEmp.php">Empleados</a>
                    </div>
                </li>
                <li>
                    <div class="menu-item">
                        <img src="/Soluciones/Static/Img/cliente.png" class="small-image" alt="Clientes">
                        <a href="#">Clientes</a>
                    </div>
                </li>
                <li>
                    <div class="menu-item">
                        <img src="/Soluciones/Static/Img/bd.png" class="small-image" alt="Base de datos">
                        <a href="#">Base de datos</a>
                    </div>
                </li>
                <li>
                    <div class="menu-item">
                        <img src="/Soluciones/Static/Img/logout.png" class="small-image" alt="Cerrar sesión">
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

    <script src="/Soluciones/Static/Controller/Js/Logout.js"></script>
</body>
</html>
