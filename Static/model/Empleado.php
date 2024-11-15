<?php

class Empleado {

    // Propiedades de la clase
    private $noEmpleado;
    private $nombre;
    private $apellido;
    private $sexo;
    private $fechaNac;
    private $fechaIngreso;
    private $sueldo;
    private $cargo;
    private $telefono;
    private $direccion;
    private $conn;

    // Constructor: se ejecuta cuando se crea una nueva instancia de la clase
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Setters
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setNoEmpleado($noEmpleado){
        $this->noEmpleado = $noEmpleado;
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    public function setFechaNac($fechaNac) {
        $this->fechaNac = $fechaNac;
    }

    public function setFechaIngreso($fechaIngreso) {
        $this->fechaIngreso = $fechaIngreso;
    }

    public function setSueldo($sueldo) {
        $this->sueldo = $sueldo;
    }

    public function setCargo($cargo) {
        $this->cargo = $cargo;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    // Getters
    public function getNombre() {
        return $this->nombre;
    }

    public function getNoEmpleado(){
        return $this->noEmpleado;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function getSexo() {
        return $this->sexo;
    }

    public function getFechaNac() {
        return $this->fechaNac;
    }

    public function getFechaIngreso() {
        return $this->fechaIngreso;
    }

    public function getSueldo() {
        return $this->sueldo;
    }

    public function getCargo() {
        return $this->cargo;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    // Métodos para manejar empleados
    public function obtenerEmpleado($idEmpleado) {
        $sql = "SELECT * FROM empleado WHERE noEmpleado = ? and noEmpleado NOT IN (1, 2);";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idEmpleado);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function obtenerEmpleados() {
        // Consulta para obtener los empleados
        $sql = "SELECT * FROM empleado where noEmpleado not in (1,2)";
        $resultado = mysqli_query($this->conn, $sql);
        $empleados = [];
        while ($fila = $resultado->fetch_assoc()) {
            $empleados[] = $fila;
        }
        return $empleados;
    }

    public function existeEmpleado() {
        $query = "SELECT * FROM empleado WHERE nombre = ? AND apellido = ?
        AND fechaNac = ? AND telefono = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssss", $this->nombre, $this->apellido, $this->fechaNac,
        $this->telefono);
        $stmt->execute();
        $resultado = $stmt->get_result();

        return $resultado->num_rows > 0; // Retorna verdadero si el empleado existe
    }

    public function insertarEmpleado() {
        // Preparar la consulta de inserción
        $sql = "INSERT INTO empleado (nombre, apellido, sexo, fechaNac, fechaIngreso, sueldo, cargo, telefono, direccion) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            // Asociar los parámetros de la consulta con los valores del objeto Empleado
            $stmt->bind_param(
                "sssssdsss",
                $this->nombre,
                $this->apellido,
                $this->sexo,
                $this->fechaNac,
                $this->fechaIngreso,
                $this->sueldo,
                $this->cargo,
                $this->telefono,
                $this->direccion            
            );
            // Ejecutar la consulta
            if ($stmt->execute()) {
                header('Location: /SolucionesWeb/Static/View/Admin/ViewGestionEmp.php');
                exit;
            } else {
                echo "Error al crear empleado: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error en la preparación de la consulta: " . $this->conn->error;
        }
    }

    public function eliminarEmpleado($id){
        $sql = "DELETE FROM empleado WHERE noEmpleado = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    public function modificarEmpleado() {
        // Prepare la consulta SQL
        $query = "UPDATE empleado SET 
                    nombre = ?, 
                    apellido = ?, 
                    sexo = ?, 
                    fechaNac = ?, 
                    fechaIngreso = ?, 
                    sueldo = ?, 
                    cargo = ?, 
                    telefono = ?, 
                    direccion = ?, 
                  WHERE noEmpleado = ?";
        // Preparar la sentencia
        if ($stmt = $this->conn->prepare($query)) {
            // Obtener los datos del objeto empleado
            $nombre = $this->nombre;
            $apellido = $this->apellido;
            $sexo = $this->sexo;
            $fechaNac = $this->fechaNac;
            $fechaIngreso = $this->fechaIngreso;
            $sueldo = $this->sueldo;
            $cargo = $this->cargo;
            $telefono = $this->telefono;
            $direccion = $this->direccion;
            $noEmpleado = $this->noEmpleado;
            // Vincular parámetros
            $stmt->bind_param("sssssdsssd", 
                $nombre, 
                $apellido, 
                $sexo, 
                $fechaNac, 
                $fechaIngreso, 
                $sueldo, 
                $cargo, 
                $telefono, 
                $direccion, 
                $noEmpleado
            );
            // Ejecutar la consulta
            if ($stmt->execute()) {
                return true; // Actualización exitosa
            } else {
                echo "Error en la actualización: " . $stmt->error;
                return false; // Error en la actualización
            }
    
        } else {
            echo "Error al preparar la consulta: " . $this->conn->error;
            return false; // Error al preparar la consulta
        }
    }

    public function filtrarEmpleado($parametro) {
        // Preparar la consulta SQL
        $sql = "SELECT * FROM empleado 
                WHERE noEmpleado NOT IN (1, 2) and (nombre LIKE ? OR apellido LIKE ?);";
        // Preparar la sentencia
        if ($stmt = $this->conn->prepare($sql)) {
            // Agregar los caracteres comodín para la búsqueda
            $parametro = '%' . $parametro . '%'; // Para que busque cualquier coincidencia
            $stmt->bind_param("ss", $parametro, $parametro);
            // Ejecutar la consulta
            $stmt->execute();
            // Obtener el resultado
            $resultado = $stmt->get_result();
            // Crear un array para almacenar los empleados filtrados
            $empleadosFiltrados = [];
            // Recorrer el resultado y agregarlo al array
            while ($fila = $resultado->fetch_assoc()) {
                $empleadosFiltrados[] = $fila;
            }
            // Devolver el array de empleados filtrados
            return $empleadosFiltrados;
        } else {
            echo "Error al preparar la consulta: " . $this->conn->error;
            return []; // Retornar un array vacío en caso de error
        }
    }    

}

?>
