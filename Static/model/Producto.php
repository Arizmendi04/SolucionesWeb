<?php

class Producto {

    // Propiedades de la clase
    private $folio;
    private $nombreProd;
    private $tipo;
    private $unidadM;
    private $existencia;
    private $peso;
    private $descripcion;
    private $precio;
    private $urlImagen;
    private $idProveedor;
    private $proveedorNombre;
    private $conn;

    // Constructor: se ejecuta cuando se crea una nueva instancia de la clase
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Setters
    public function setFolio($folio) {
        $this->folio = $folio;
    }

    public function setProveedorNombre($proveedorNombre){
        $this->proveedorNombre = $proveedorNombre;
    }

    public function setNombreProd($nombreProd) {
        $this->nombreProd = $nombreProd;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function setUnidadM($unidadM) {
        $this->unidadM = $unidadM;
    }

    public function setExistencia($existencia) {
        $this->existencia = $existencia;
    }

    public function setPeso($peso) {
        $this->peso = $peso;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    public function setUrlImagen($urlImagen) {
        $this->urlImagen = $urlImagen;
    }

    public function setIdProveedor($idProveedor) {
        $this->idProveedor = $idProveedor;
    }

    // Getters
    public function getFolio() {
        return $this->folio;
    }

    public function getNombreProd() {
        return $this->nombreProd;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getUnidadM() {
        return $this->unidadM;
    }

    public function getExistencia() {
        return $this->existencia;
    }

    public function getPeso() {
        return $this->peso;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function getUrlImagen() {
        return $this->urlImagen;
    }

    public function getIdProveedor() {
        return $this->idProveedor;
    }

    public function getProveedorNombre(){
        return $this->proveedorNombre;
    }

    // Métodos para manejar productos
    public function obtenerProducto($folio) {
        $sql = "SELECT * FROM producto WHERE folio = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $folio);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function obtenerProductos() {
        // Consulta para obtener todos los productos
        $sql = "SELECT * FROM producto";
        $resultado = mysqli_query($this->conn, $sql);
        $productos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $productos[] = $fila;
        }
        return $productos;
    }

    public function obtenerProductosPorProveedor($nombreProducto, $idProveedor) {
        $query = "SELECT folio, nombreProd FROM producto WHERE nombreProd LIKE ? AND idProveedor = ?";
        $stmt = $this->conn->prepare($query);
        $nombreProducto = "%$nombreProducto%";
        $stmt->bind_param("si", $nombreProducto, $idProveedor);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    

    public function insertarProducto() {
        // Preparar la consulta de inserción
        $sql = "INSERT INTO producto (nombreProd, tipo, unidadM, existencia, peso, descripcion, precio, urlImagen, idProveedor) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            // Asociar los parámetros de la consulta con los valores del objeto Producto
            // Cambiamos a "sssidsssi" porque ahora solo hay 9 campos
            $stmt->bind_param(
                "sssidsssi", // Aquí usamos un 'i' para idProveedor como entero
                $this->nombreProd,
                $this->tipo,
                $this->unidadM,
                $this->existencia,
                $this->peso,
                $this->descripcion,
                $this->precio,
                $this->urlImagen,
                $this->idProveedor // Asegúrate de que este sea un entero
            );
            // Ejecutar la consulta
            if ($stmt->execute()) {
                header('Location: /SolucionesWeb/Static/View/Admin/ViewGestionProd.php');
                exit;
            } else {
                echo "Error al crear producto: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error en la preparación de la consulta: " . $this->conn->error;
        }
    }    

    public function eliminarProducto($folio) {
        $sql = "DELETE FROM producto WHERE folio = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $folio);
        return $stmt->execute();
    }
    
    public function modificarProducto() {
        // Preparar la consulta SQL
        $query = "UPDATE producto SET 
                    nombreProd = ?, 
                    tipo = ?, 
                    unidadM = ?, 
                    existencia = ?, 
                    peso = ?, 
                    descripcion = ?, 
                    precio = ?, 
                    urlImagen = ?, 
                    idProveedor = ? 
                WHERE folio = ?";
        // Preparar la sentencia
        if ($stmt = $this->conn->prepare($query)) {
            // Obtener los datos del objeto producto
            $nombreProd = $this->nombreProd;
            $tipo = $this->tipo;
            $unidadM = $this->unidadM;
            $existencia = $this->existencia;
            $peso = $this->peso;
            $descripcion = $this->descripcion;
            $precio = $this->precio;
            $urlImagen = $this->urlImagen;
            $idProveedor = $this->idProveedor;
            $folio = $this->folio;
            // Vincular parámetros
            $stmt->bind_param("sssidsdsii", 
                $nombreProd, 
                $tipo, 
                $unidadM, 
                $existencia, 
                $peso, 
                $descripcion, 
                $precio, 
                $urlImagen, 
                $idProveedor,
                $folio
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

    public function filtrarProducto($parametro) {
        // Preparar la consulta SQL
        $sql = "SELECT * FROM producto WHERE nombreProd LIKE ? OR descripcion LIKE ?";
        // Preparar la sentencia
        if ($stmt = $this->conn->prepare($sql)) {
            // Agregar los caracteres comodín para la búsqueda
            $parametro = '%' . $parametro . '%'; // Para que busque cualquier coincidencia
            $stmt->bind_param("ss", $parametro, $parametro);
            // Ejecutar la consulta
            $stmt->execute();
            // Obtener el resultado
            $resultado = $stmt->get_result();
            // Crear un array para almacenar los productos filtrados
            $productosFiltrados = [];
            // Recorrer el resultado y agregarlo al array
            while ($fila = $resultado->fetch_assoc()) {
                $productosFiltrados[] = $fila;
            }
            // Devolver el array de productos filtrados
            return $productosFiltrados;
        } else {
            echo "Error al preparar la consulta: " . $this->conn->error;
            return []; // Retornar un array vacío en caso de error
        }
    }    

}

?>
