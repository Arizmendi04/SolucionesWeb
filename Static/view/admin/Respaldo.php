<?php 
include 'HeaderA.php'; 
include '../../Controller/Sesion.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respaldo y Subida de Base de Datos</title>
    <link rel="stylesheet" href="../../css/bd.css">
    <script>
        // Función para validar si se ha seleccionado un archivo antes de enviar el formulario
        function validateFile() {
            var fileInput = document.getElementById('file-upload');
            var errorMessage = document.getElementById('error-message');

            // Verificar si el campo de archivo está vacío
            if (!fileInput.value) {
                // Mostrar mensaje de error
                errorMessage.textContent = 'Por favor, selecciona un archivo para importar.';
                errorMessage.style.color = 'red';
                return false; // Impide el envío del formulario
            }

            // Si se seleccionó un archivo, se limpia el mensaje de error
            errorMessage.textContent = '';
            return true; // Permite el envío del formulario
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Respaldo y Subida de Base de Datos</h1>

        <!-- Mostrar mensaje de éxito o error -->
        <?php
        if (isset($_GET['message'])) {
            $message = $_GET['message'];
            if ($message == 'export_success') {
                echo '<p style="color: green;">Exportación a CSV realizada con éxito.</p>';
            } elseif ($message == 'import_success') {
                echo '<p style="color: green;">Importación de datos realizada con éxito.</p>';
            } elseif ($message == 'export_error') {
                echo '<p style="color: red;">Hubo un error al intentar exportar los datos.</p>';
            } elseif ($message == 'import_error') {
                echo '<p style="color: red;">Hubo un error al intentar importar los datos.</p>';
            }
        }
        ?>

        <!-- Formulario para exportar datos (a CSV) -->
        <div class="form-container">
            <h2>Exportar Datos</h2>

            <form action="/SolucionesWeb/Static/Controller/ControllerRespaldo.php" method="post" enctype="multipart/form-data">
                <button type="submit" name="exportarCSV" class="btn">Exportar a CSV</button>
            </form>

            <br>

            <form action="/SolucionesWeb/Static/Controller/ControllerRespaldo.php" method="post" enctype="multipart/form-data">
                <button type="submit" name="exportarSQL" class="btn">Exportar a SQL</button>
            </form>

        </div>

        <!-- Formulario para importar datos (CSV o Excel) -->
        <div class="form-container">
            <h2>Importar Datos</h2>
            <form id="importForm" action="/SolucionesWeb/Static/Controller/ControllerRespaldo.php" method="post" enctype="multipart/form-data" onsubmit="return validateFile()">
                <!-- Botón de selección de archivo -->
                <label for="file-upload" class="file-upload-label">Seleccionar archivo</label>
                <input type="file" name="file" id="file-upload" accept=".csv, .xlsx, .xls" required>
                
                <!-- Botón de carga -->
                <button type="submit" name="import" class="btn">Cargar Archivo</button>
            </form>

            <!-- Contenedor para mostrar el mensaje de error -->
            <div id="error-message"></div>
        </div>
    </div>
</body>
</html>
