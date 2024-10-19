<?php include "../controller/connect/db.php"?>
 
<?php
    session_start();
    session_destroy();
    header("Location: ../../index.php");
?>