<?php include "../Controller/Connect/Db.php"?>
 
<?php
    session_start();
    session_destroy();
    header("Location: ../../index.php");
?>