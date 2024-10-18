<?php 
    $conn = mysqli_connect(
        'localhost',  // Cambié la URL al localhost para desarrollo local
        'root',       // Usuario de la base de datos
        '',           // Contraseña (vacía por defecto en algunos sistemas)
        'SoluPlagas'  // Nombre de la base de datos
    );

    // Verificación de la conexión
    if (!$conn) {
        die("Error en la conexión: " . mysqli_connect_error());
    }
?>
