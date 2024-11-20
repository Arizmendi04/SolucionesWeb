<?php include '../../Controller/Sesion.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SolucionesWeb Para Plagas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Viga&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../Css/Dashboard.css">
</head>
<body>

    <div class="sidebar">
        <div class="user-profile">
            <img src="/SolucionesWeb/Static/Img/cliente.png" alt="Admin" class="user-image">
            <p class="username">Empleado</p>
        </div>
        <nav>
            <ul>
                <li>
                    <div class="menu-item">
                        <img src="/SolucionesWeb/Static/Img/dash.png" class="small-image" alt="Dashboard">
                        <a href="/SolucionesWeb/Static/View/Empleado/DashboardEmp.php">Dashboard</a>
                    </div>
                </li>
                <li>
                    <div class="menu-item">
                        <img src="/SolucionesWeb/Static/Img/ventas.png" class="small-image" alt="Ventas">
                        <a href="/SolucionesWeb/Static/View/Empleado/ViewGestionTickets.php">Ventas</a>
                    </div>
                </li>
                <li>
                    <div class="menu-item">
                        <img src="/SolucionesWeb/Static/Img/productos.png" class="small-image" alt="Productos">
                        <a href="/SolucionesWeb/Static/View/Empleado/ViewGestionProd.php">Productos</a>
                    </div>
                </li>
                <li>
                    <div class="menu-item">
                        <img src="/SolucionesWeb/Static/Img/proveedor.png" class="small-image" alt="Proveedores">
                        <a href="/SolucionesWeb/Static/View/Empleado/ViewGestionProve.php">Proveedores</a>
                    </div>
                </li>
                <li>
                    <div class="menu-item">
                        <img src="/SolucionesWeb/Static/Img/logout.png" class="small-image" alt="Cerrar sesión">
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
        <button class="alert-buttonCance" onclick="closeAlert()">Cancelar</button>
    </div>

    <script src="/SolucionesWeb/Static/Controller/Js/Logout.js"></script>
</body>
</html>
