<?php
    
    session_start();
    include __DIR__ . '/Connect/Db.php';

    if (!$conn) {
        header("Location: ../View/Login.php");
        exit;
    }
    $user = $_POST["usuario"];
    $password = $_POST["contrasena"];

    $sql = "SELECT * FROM usuario WHERE nombreU = '$user' AND contrasena = '$password'";
    $execute = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_array($execute)) {
        // Si las credenciales son correctas, configurar sesión y redirigir
        $_SESSION["usuario"] = $user;
        $_SESSION["noEmpleado"] = $row["noEmpleado"]; // Guardar el noEmpleado en la sesión
        if ($row["tipoU"] === "Admin") {
            header("Location: /SolucionesWeb/Static/View/Admin/DashboardAdmin.php");
        } elseif ($row["tipoU"] === "Empleado") {
            header("Location: /SolucionesWeb/Static/View/Empleado/DashboardEmp.php");
        } else {
            header("Location: /SolucionesWeb/Static/View/Login.php");
        }
    } else {
        // Si las credenciales son incorrectas, guardar mensaje de error y redirigir
        $_SESSION["mensaje_error"] = "Usuario o contraseña incorrectos.";
        header("Location: /SolucionesWeb/Static/View/Login.php");
        exit();
    }
?>
