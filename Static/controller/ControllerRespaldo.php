<?php
    include 'Sesion.php'; 
    include 'Connect/RespRest.php';

    if (isset($_POST['exportarSQL'])) {
        // Llamamos a la función para exportar la base de datos a un archivo SQL
        exportToSQL();
        header('Location: /SolucionesWeb/Static/View/Admin/Respaldo.php?message=export_success');
        exit();
    }

    if (isset($_POST['exportarCSV'])) {
        // Llamamos a la función para exportar la base de datos a un archivo CSV
        exportToCSV(); 
        header('Location: /SolucionesWeb/Static/View/Admin/Respaldo.php?message=export_success');
        exit();
    }

    if (isset($_POST['import']) && isset($_FILES['file'])) {
        $file = $_FILES['file']; // Obtenemos el archivo subido
        $fileType = pathinfo($file['name'], PATHINFO_EXTENSION); // Obtenemos la extensión del archivo
        
        // Determinamos el tipo de archivo y llamamos a la función correspondiente
        if ($fileType == 'csv') {
            importCSV($file);
            header('Location: /SolucionesWeb/Static/View/Admin/Respaldo.php?message=import_success');
            exit();
        } elseif (in_array($fileType, ['xls', 'xlsx'])) {
            importExcel($file);
            header('Location: /SolucionesWeb/Static/View/Admin/Respaldo.php?message=import_success');
            exit();
        } elseif ($fileType == 'sql') {
            importSQL($file);
            header('Location: /SolucionesWeb/Static/View/Admin/Respaldo.php?message=import_success');
            exit();
        } else {
            echo "Formato de archivo no soportado.";
            exit();
        }
    }

    // Función para importar datos desde un archivo SQL
    function importSQL($file) {
        global $conn; // Usamos la conexión a la base de datos

        // Leemos el contenido del archivo SQL y separamos las consultas
        $sqlContent = file_get_contents($file['tmp_name']);
        $queries = explode(";", $sqlContent);
        
        foreach ($queries as $query) {
            $query = trim($query); // Limpiamos la consulta
            if (!empty($query)) { // Verificamos que la consulta no esté vacía
                try {
                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                } catch (Exception $e) {
                    echo "Error al ejecutar la consulta: " . $e->getMessage();
                    exit();
                }
            }
        }

        echo "Archivo SQL importado y base de datos actualizada exitosamente.";
    }

    function exportToSQL() {
        global $conn;

        // Redirigimos para obtener la lista de tablas en la base de datos
        $sql = "SHOW TABLES";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Llamamos a la creación del nombre de archivo con la fecha actual
        $filename = "BackupBD" . date("Y-m-d_H-i-s") . ".sql";
        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $output = fopen('php://output', 'w');

        // Escribimos el encabezado para la base de datos
        fwrite($output, "DROP DATABASE IF EXISTS soluPlagas;\n");
        fwrite($output, "CREATE DATABASE soluPlagas;\n");
        fwrite($output, "USE soluPlagas;\n\n");

        // Iteramos sobre las tablas y exportamos cada una
        foreach ($tables as $table) {
            fwrite($output, "DROP TABLE IF EXISTS $table;\n");

            // Llamamos a la función para obtener el script de creación de la tabla
            $createTableSQL = generateCreateTableSQL($table);
            fwrite($output, $createTableSQL . ";\n\n");

            // Llamamos a la función para generar el script de inserción de datos
            $insertSQL = generateInsertSQL($table);
            fwrite($output, $insertSQL . "\n\n");
        }

        fclose($output);
        exit();
    }

    // Función para generar el script de creación de la tabla
    function generateCreateTableSQL($table) {
        global $conn;

        // Llamamos al comando SHOW CREATE TABLE para obtener la definición de la tabla
        $sql = "SHOW CREATE TABLE $table";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['Create Table']; // Retornamos el script de creación de la tabla
    }

    function generateInsertSQL($table) {
        global $conn;
        // Llamamos al comando SELECT para obtener todos los registros de la tabla
        $sql = "SELECT * FROM $table";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($rows)) {
            return "-- No hay datos en esta tabla."; // Si la tabla está vacía, retornamos un comentario
        }

        // Llamamos a la obtención de las columnas de la tabla
        $columns = array_keys($rows[0]);
        $columnNames = implode(", ", $columns);
        $insertSQL = "INSERT INTO $table ($columnNames) VALUES ";

        $values = [];
        foreach ($rows as $row) {
            // Preparamos los valores de las filas para el script de inserción
            $rowValues = array_map(function($value) {
                return $value === null ? "NULL" : "'" . addslashes($value) . "'";
            }, array_values($row));
            $values[] = "(" . implode(", ", $rowValues) . ")";
        }

        $insertSQL .= implode(",\n", $values) . ";"; // Unimos todas las inserciones en un solo script

        return $insertSQL; // Retornamos el script de inserción
    }

    function exportToCSV() {
        global $conn;

        $filename = "BackupBD" . date("Y-m-d_H-i-s") . ".csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $output = fopen('php://output', 'w');

        $sql = "SHOW TABLES";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            fputcsv($output, ["Tabla: $table"]);

            $sql = "SELECT * FROM $table";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Escribimos cada fila de la tabla en el archivo CSV
            foreach ($rows as $row) {
                fputcsv($output, $row);
            }

            fputcsv($output, []); // Añadimos una línea vacía después de cada tabla
        }

        fclose($output);
        exit();
    }

    function importCSV($file) {
        global $conn;

        $fileTmpName = $file['tmp_name'];
        $fileHandle = fopen($fileTmpName, 'r');
        
        $currentTable = '';
        while (($row = fgetcsv($fileHandle)) !== false) {
            // Verificamos si la fila contiene el nombre de la tabla
            if (strpos($row[0], 'Tabla: ') === 0) {
                $currentTable = substr($row[0], 7); // Extraemos el nombre de la tabla
                continue;
            }
            
            // Si la fila tiene datos, los insertamos en la tabla correspondiente
            if ($currentTable && count($row) > 1) {
                $columns = implode(',', array_keys($row));
                $placeholders = implode(',', array_fill(0, count($row), '?'));

                // Llamamos a la creación de la consulta de inserción
                $sql = "INSERT INTO $currentTable ($columns) VALUES ($placeholders)";
                $stmt = $conn->prepare($sql);
                $stmt->execute($row);
            }
        }
        
        fclose($fileHandle); // Cerramos el archivo CSV
        echo "Datos importados exitosamente.";
    }

    function importExcel($file) {
        global $conn;

        require 'vendor/autoload.php';

        $inputFileName = $file['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $sheet = $spreadsheet->getActiveSheet();

        $currentTable = '';
        foreach ($sheet->getRowIterator() as $rowIndex => $row) {
            if ($rowIndex == 1) continue; // Ignoramos la primera fila (cabeceras)

            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $rowData[] = $cell->getValue(); // Obtenemos los valores de cada celda
            }

            if ($currentTable) { 
                // Llamamos a la creación de la consulta de inserción
                $sql = "INSERT INTO $currentTable (columna1, columna2, columna3) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute($rowData); // Ejecutamos la inserción
            }
        }

        echo "Datos importados exitosamente desde Excel.";
    }
?>
