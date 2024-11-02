<?php include __DIR__ . '/Connect/Db.php'; ?>

<?php

    session_start();

    $user = $_POST["usuario"];
    $password = $_POST["contrasena"];

    $sql = "Select * from usuario where nombreU = '$user' and contrasena = '$password'";

    $execute = mysqli_query($conn, $sql);

    $row = mysqli_fetch_array($execute);

    if(($row["nombreU"] == $user) && ($row["contrasena"] == $password)){
        $_SESSION["usuario"] = $user;
        if ($row["tipoU"] === "Admin") {
            header("Location: /SolucionesWeb/Static/View/Admin/DashboardAdmin.php");
        } elseif ($row["tipoU"] === "Empleado") {
            header("Location: /SolucionesWeb/Static/View/Empleado/DashboardEmp.php");
        } else {
            header("Location: /SolucionesWeb/Static/View/Login.php");
        }
    }else{
        header("Location: /SolucionesWeb/Static/View/Login.php");
    }

?>