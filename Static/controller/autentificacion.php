<?php include 'Connect/Db.php';?>

<?php
    $user = $_POST["usuario"];
    $password = $_POST["contrasena"];

    $sql = "Select * from usuario where nombreU = '$user' and contrasena = '$password'";

    $execute = mysqli_query($conn, $sql);

    $row = mysqli_fetch_array($execute);

    if(($row["nombreU"] == $user) && ($row["contrasena"] == $password)){
        $_SESSION["usuario"] = $user;
        if ($row["tipoU"] === "admin") {
            header("Location: ../View/Admin/DashboardAdmin.php");
        } elseif ($row["tipoU"] === "usuario") {
            header("Location: ../View/Empleado/DashboardEmp.php");
        } else {
            header("Location: ../View/Login.php");
        }
    }else{
        header("Location: ../View/Login.php");
    }

?>