<?php
try {
    $conn = new PDO('mysql:host=localhost;dbname=danielt2_plagas2024', 'root', '');
    // Establecer el modo de error de PDO para que se pueda ver cualquier error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>
