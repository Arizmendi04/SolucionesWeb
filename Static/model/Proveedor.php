<?php
    include($_SERVER['DOCUMENT_ROOT'].'/SolucionesWeb/Static/Controller/Sesion.php');
?>

<?php
    class Proveedor {

        // Propiedades de la clase
        private $idProveedor;
        private $razonSocial;
        private $nombreComercial;
        private $telefono;
        private $correo;
        private $conn;

        // Constructor: se ejecuta cuando se crea una nueva instancia de la clase
        public function __construct($conn) {
            $this->conn = $conn;
        }

        // Setters
        public function setIdProveedor($idProveedor) {
            $this->idProveedor = $idProveedor;
        }

        public function setRazonSocial($razonSocial) {
            $this->razonSocial = $razonSocial;
        }

        public function setNombreComercial($nombreComercial) {
            $this->nombreComercial = $nombreComercial;
        }

        public function setTelefono($telefono) {
            $this->telefono = $telefono;
        }

        public function setCorreo($correo) {
            $this->correo = $correo;
        }

        // Getters
        public function getIdProveedor() {
            return $this->idProveedor;
        }

        public function getRazonSocial() {
            return $this->razonSocial;
        }

        public function getNombreComercial() {
            return $this->nombreComercial;
        }

        public function getTelefono() {
            return $this->telefono;
        }

        public function getCorreo() {
            return $this->correo;
        }

        // Métodos para manejar proveedores
        public function obtenerProveedor($idProveedor) {
            $sql = "SELECT * FROM proveedor WHERE idProveedor = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $idProveedor);
            $stmt->execute();
            $resultado = $stmt->get_result();
            return $resultado->fetch_assoc();
        }

        public function obtenerProveedores() {
            $sql = "SELECT * FROM proveedor";
            $resultado = mysqli_query($this->conn, $sql);
            $proveedores = [];
            while ($fila = $resultado->fetch_assoc()) {
                $proveedores[] = $fila;
            }
            return $proveedores;
        }
        
        public function insertarProveedor() {
            $sql = "INSERT INTO proveedor (razonSocial, nombreComercial, telefono, correo) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ssss",
                    $this->razonSocial,
                    $this->nombreComercial,
                    $this->telefono,
                    $this->correo
                );
                if ($stmt->execute()) {
                    header('Location: /SolucionesWeb/Static/View/Admin/ViewGestionProve.php');
                    exit;
                } else {
                    echo "Error al crear proveedor: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Error en la preparación de la consulta: " . $this->conn->error;
            }
        }

        public function eliminarProveedor($id) {
            $sql = "DELETE FROM proveedor WHERE idProveedor = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }
        
        public function modificarProveedor() {
            $query = "UPDATE proveedor SET 
                        razonSocial = ?, 
                        nombreComercial = ?, 
                        telefono = ?, 
                        correo = ? 
                    WHERE idProveedor = ?";
            if ($stmt = $this->conn->prepare($query)) {
                $stmt->bind_param("ssssd", 
                    $this->razonSocial, 
                    $this->nombreComercial, 
                    $this->telefono, 
                    $this->correo,
                    $this->idProveedor
                );
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

        // Método para filtrar proveedores por nombre comercial o razón social
        public function filtrarProveedor($parametro) {
            $sql = "SELECT * FROM proveedor WHERE nombreComercial LIKE ? OR razonSocial LIKE ?";
            $stmt = $this->conn->prepare($sql);
            if ($stmt) {
                $parametro = '%' . $parametro . '%';
                $stmt->bind_param("ss", $parametro, $parametro);
                $stmt->execute();
                $resultado = $stmt->get_result();
                $proveedoresFiltrados = [];
                while ($fila = $resultado->fetch_assoc()) {
                    $proveedoresFiltrados[] = $fila;
                }
                return $proveedoresFiltrados;
            } else {
                echo "Error al preparar la consulta: " . $this->conn->error;
                return [];
            }
        }
    }
?>
