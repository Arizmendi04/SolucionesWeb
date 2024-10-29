<?php include "../Controller/Connect/Db.php"?>
<?php include 'Sesion.php'; ?>

<?php
    session_start();
    session_destroy();
    header("Location: ../View/Login.php");
?>