<?php include 'connect/db.php';?>

<?php
    $user = $_POST["usuario"];
    $password = $_POST["contrasena"];

    $sql = "Select * from usuario where nombreU = '$user' and contrasena = '$password'";

    $execute = mysqli_query($conn, $sql);

    $row = mysqli_fetch_array($execute);

    if(($row["nombreU"] == $user) && ($row["contrasena"] == $password)){
        $_SESSION["usuario"] = $user;
        if ($row["tipoU"] === "admin") {
            header("Location: ../view/admin/dashboardadmin.html");
        } elseif ($row["tipoU"] === "usuario") {
            header("Location: ../view/empleado/dashboardempleado.html");
        } else {
            header("Location: ../view/login.php");
        }
    }else{
        header("Location: ../view/login.php");
    }

?>