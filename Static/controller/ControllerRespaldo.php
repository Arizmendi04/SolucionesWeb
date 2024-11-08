<?php
    include 'Sesion.php'; 
    include 'Connect/RespRest.php';

    // Exportar datos a SQL
    if (isset($_POST['exportarSQL'])) {
        exportToSQL();
        header('Location: /SolucionesWeb/Static/View/Admin/Respaldo.php?message=export_success');
        exit();
    }

    // Exportar datos a CSV 
    if (isset($_POST['exportarCSV'])) {
        exportToCSV(); 
        header('Location: /SolucionesWeb/Static/View/Admin/Respaldo.php?message=export_success');
        exit();
    }

    // Importar datos desde un archivo (CSV, Excel o SQL)
    if (isset($_POST['import']) && isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $fileType = pathinfo($file['name'], PATHINFO_EXTENSION);
        
        // Verificar tipo de archivo y proceder a la importación correspondiente
        if ($fileType == 'csv') {
            importCSV($file);
            header('Location: /SolucionesWeb/Static/View/Admin/Respaldo.php?message=import_success');
            exit();
        } elseif (in_array($fileType, ['xls', 'xlsx'])) {
            importExcel($file);
            header('Location: /SolucionesWeb/Static/View/Admin/Respaldo.php?message=import_success');
            exit();
        } elseif ($fileType == 'sql') {
            importSQL($file);  // Importar archivo SQL
            header('Location: /SolucionesWeb/Static/View/Admin/Respaldo.php?message=import_success');
            exit();
        } else {
            echo "Formato de archivo no soportado.";
            exit();
        }
    }

    // Función para importar datos desde un archivo SQL
    function importSQL($file) {
        global $conn;  // Usamos la conexión PDO

        // Leer el contenido del archivo SQL
        $sqlContent = file_get_contents($file['tmp_name']);

        // Separar las instrucciones SQL por ";"
        $queries = explode(";", $sqlContent);
        
        // Recorrer todas las consultas y ejecutarlas
        foreach ($queries as $query) {
            $query = trim($query);
            
            if (!empty($query)) {
                try {
                    // Ejecutar la consulta SQL
                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                } catch (Exception $e) {
                    // Si hay un error, mostrarlo
                    echo "Error al ejecutar la consulta: " . $e->getMessage();
                    exit();
                }
            }
        }

        echo "Archivo SQL importado y base de datos actualizada exitosamente.";
    }

    // Función para exportar toda la base de datos a SQL (estructura + datos)
    function exportToSQL() {
        global $conn;

        // Obtener todas las tablas de la base de datos
        $sql = "SHOW TABLES";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);  // Obtén solo los nombres de las tablas

        // Crear un archivo SQL
        $filename = "BackupBD" . date("Y-m-d_H-i-s") . ".sql";
        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $output = fopen('php://output', 'w');

        // Escribir el comando para eliminar la base de datos si existe
        fwrite($output, "DROP DATABASE IF EXISTS danielt2_plagas2024;\n");
        fwrite($output, "CREATE DATABASE danielt2_plagas2024;\n");
        fwrite($output, "USE danielt2_plagas2024;\n\n");

        // Recorremos todas las tablas y exportamos su estructura y datos
        foreach ($tables as $table) {
            // Escribir el comando DROP para cada tabla (para evitar duplicados en la restauración)
            fwrite($output, "DROP TABLE IF EXISTS $table;\n");

            // Escribir la sentencia CREATE TABLE para la tabla
            $createTableSQL = generateCreateTableSQL($table);
            fwrite($output, $createTableSQL . "\n\n");

            // Escribir las sentencias INSERT INTO para los datos
            $insertSQL = generateInsertSQL($table);
            fwrite($output, $insertSQL . "\n\n");
        }

        fclose($output);
        exit();
    }

    // Función para generar la sentencia CREATE TABLE
    function generateCreateTableSQL($table) {
        global $conn;

        // Obtener la estructura de la tabla
        $sql = "SHOW CREATE TABLE $table";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Devolver la sentencia CREATE TABLE
        return $result['Create Table'];
    }

    // Función para generar las sentencias INSERT INTO para los datos
    function generateInsertSQL($table) {
        global $conn;

        // Obtener todos los registros de la tabla
        $sql = "SELECT * FROM $table";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Si no hay registros, no generamos INSERT
        if (empty($rows)) {
            return "-- No hay datos en esta tabla.";
        }

        // Generar las sentencias INSERT
        $columns = array_keys($rows[0]);
        $columnNames = implode(", ", $columns);
        $insertSQL = "INSERT INTO $table ($columnNames) VALUES ";

        $values = [];
        foreach ($rows as $row) {
            $rowValues = array_map(function($value) {
                // Verificar si el valor es NULL y evitar el uso de addslashes en ese caso
                if ($value === null) {
                    return "NULL";  // Si es NULL, lo dejamos sin comillas
                }
                // Si no es NULL, escapamos el valor con addslashes
                return "'" . addslashes($value) . "'";
            }, array_values($row));
            $values[] = "(" . implode(", ", $rowValues) . ")";
        }

        // Unir todas las filas de inserción en una sola sentencia
        $insertSQL .= implode(",\n", $values) . ";";

        return $insertSQL;
    }

    // Función para exportar datos a CSV
    function exportToCSV() {
        global $conn;

        // Crear un archivo CSV
        $filename = "BackupBD" . date("Y-m-d_H-i-s") . ".csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $output = fopen('php://output', 'w');

        // Obtener todas las tablas de la base de datos
        $sql = "SHOW TABLES";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Recorremos todas las tablas y exportamos su estructura y datos
        foreach ($tables as $table) {
            // Escribir encabezado de la tabla en el CSV
            fputcsv($output, ["Tabla: $table"]);

            // Obtener los datos de la tabla
            $sql = "SELECT * FROM $table";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Escribir los datos en el CSV
            foreach ($rows as $row) {
                fputcsv($output, $row);
            }

            // Separar tablas con una línea vacía
            fputcsv($output, []);
        }

        fclose($output);
        exit();
    }

    // Función para importar datos desde un archivo CSV
    function importCSV($file) {
        global $conn;  // Usamos la conexión PDO

        $fileTmpName = $file['tmp_name'];
        $fileHandle = fopen($fileTmpName, 'r');
        
        // Leer todo el archivo CSV y procesarlo por tablas
        $currentTable = '';
        while (($row = fgetcsv($fileHandle)) !== false) {
            // Si la primera columna contiene "Tabla: [nombre_tabla]", significa que es el nombre de una tabla
            if (strpos($row[0], 'Tabla: ') === 0) {
                $currentTable = substr($row[0], 7); // Obtener el nombre de la tabla
                continue;  // Saltar a la siguiente fila
            }
            
            // Si hay datos, insertarlos en la tabla correspondiente
            if ($currentTable && count($row) > 1) {
                // Obtener las columnas para la tabla actual
                $columns = implode(',', array_keys($row));
                $placeholders = implode(',', array_fill(0, count($row), '?'));

                // Construir la consulta SQL de inserción
                $sql = "INSERT INTO $currentTable ($columns) VALUES ($placeholders)";
                $stmt = $conn->prepare($sql);
                $stmt->execute($row); // Insertar los datos
            }
        }
        
        fclose($fileHandle);
        echo "Datos importados exitosamente.";
    }

    // Función para importar datos desde un archivo Excel (XLS, XLSX)
    function importExcel($file) {
        global $conn;

        // Asegurarse de que la librería PhpSpreadsheet esté cargada
        require 'vendor/autoload.php';  // Asegúrate de que Composer cargue PhpSpreadsheet

        // Cargar el archivo Excel
        $inputFileName = $file['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $sheet = $spreadsheet->getActiveSheet();  // Seleccionamos la primera hoja

        // Recorrer todas las filas del archivo Excel
        $currentTable = '';
        foreach ($sheet->getRowIterator() as $rowIndex => $row) {
            if ($rowIndex == 1) continue; // Salta la primera fila (encabezado)

            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $rowData[] = $cell->getValue();
            }

            // Si tenemos una tabla actual, insertar los datos
            if ($currentTable) {
                // Aquí debes ajustar la consulta de acuerdo con la estructura de tu archivo
                // Asegúrate de tener columnas definidas para la tabla en cuestión
                $sql = "INSERT INTO $currentTable (columna1, columna2, columna3) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute($rowData);  // Ejecutar la inserción
            }
        }

        echo "Datos importados exitosamente desde Excel.";
    }
?>
