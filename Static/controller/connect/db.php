<?php
    //Datos de la conexión
    $conn = mysqli_connect(
        'localhost',
        'root',     
        '',        
        'soluPlagas' 
    );

    // Verificación de la conexión
    if (!$conn) {
        die("Error en la conexión: " . mysqli_connect_error());
    }
?>
