<?php 
    $conn = mysqli_connect(
        'localhost',
        'root',     
        '',        
        'danielt2_plagas2024' 
    );

    // Verificación de la conexión
    if (!$conn) {
        die("Error en la conexión: " . mysqli_connect_error());
    }
?>
