<?php include "../Controller/Connect/Db.php"?>
<?php include 'Sesion.php'; ?>

<?php
    session_start();
    session_destroy();
    header("Location: /SolucionesWeb/Static/View/Login.php");
?>