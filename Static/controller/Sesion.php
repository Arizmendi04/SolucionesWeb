<?php

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Verificar si la sesión está activa y el usuario está autenticado
    if (!isset($_SESSION['usuario'])) {
        // Redirigir a una ruta basada en el servidor
        header("Location: /SolucionesWeb/Static/View/Login.php");
        exit();
    }
    
?>
