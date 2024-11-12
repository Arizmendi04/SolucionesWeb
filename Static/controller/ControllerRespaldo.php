<?php
    include 'Sesion.php'; 
    include 'Connect/RespRest.php';

    if (isset($_POST['exportarSQL'])) {
        exportToSQL();
        header('Location: /SolucionesWeb/Static/View/Admin/Respaldo.php?message=export_success');
        exit();
    }

    if (isset($_POST['exportarCSV'])) {
        exportToCSV(); 
        header('Location: /SolucionesWeb/Static/View/Admin/Respaldo.php?message=export_success');
        exit();
    }

    if (isset($_POST['import']) && isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $fileType = pathinfo($file['name'], PATHINFO_EXTENSION);
        
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

    function importSQL($file) {
        global $conn;

        $sqlContent = file_get_contents($file['tmp_name']);
        $queries = explode(";", $sqlContent);
        
        foreach ($queries as $query) {
            $query = trim($query);
            if (!empty($query)) {
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

        $sql = "SHOW TABLES";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $filename = "BackupBD" . date("Y-m-d_H-i-s") . ".sql";
        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $output = fopen('php://output', 'w');

        fwrite($output, "DROP DATABASE IF EXISTS danielt2_plagas2024;\n");
        fwrite($output, "CREATE DATABASE danielt2_plagas2024;\n");
        fwrite($output, "USE danielt2_plagas2024;\n\n");

        foreach ($tables as $table) {
            fwrite($output, "DROP TABLE IF EXISTS $table;\n");

            $createTableSQL = generateCreateTableSQL($table);
            fwrite($output, $createTableSQL . ";\n\n");

            $insertSQL = generateInsertSQL($table);
            fwrite($output, $insertSQL . "\n\n");
        }

        fclose($output);
        exit();
    }

    function generateCreateTableSQL($table) {
        global $conn;

        $sql = "SHOW CREATE TABLE $table";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['Create Table'];
    }

    function generateInsertSQL($table) {
        global $conn;

        $sql = "SELECT * FROM $table";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($rows)) {
            return "-- No hay datos en esta tabla.";
        }

        $columns = array_keys($rows[0]);
        $columnNames = implode(", ", $columns);
        $insertSQL = "INSERT INTO $table ($columnNames) VALUES ";

        $values = [];
        foreach ($rows as $row) {
            $rowValues = array_map(function($value) {
                return $value === null ? "NULL" : "'" . addslashes($value) . "'";
            }, array_values($row));
            $values[] = "(" . implode(", ", $rowValues) . ")";
        }

        $insertSQL .= implode(",\n", $values) . ";";

        return $insertSQL;
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

            foreach ($rows as $row) {
                fputcsv($output, $row);
            }

            fputcsv($output, []);
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
            if (strpos($row[0], 'Tabla: ') === 0) {
                $currentTable = substr($row[0], 7);
                continue;
            }
            
            if ($currentTable && count($row) > 1) {
                $columns = implode(',', array_keys($row));
                $placeholders = implode(',', array_fill(0, count($row), '?'));

                $sql = "INSERT INTO $currentTable ($columns) VALUES ($placeholders)";
                $stmt = $conn->prepare($sql);
                $stmt->execute($row);
            }
        }
        
        fclose($fileHandle);
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
            if ($rowIndex == 1) continue;

            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $rowData[] = $cell->getValue();
            }

            if ($currentTable) {
                $sql = "INSERT INTO $currentTable (columna1, columna2, columna3) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute($rowData);
            }
        }

        echo "Datos importados exitosamente desde Excel.";
    }
?>
