<?php
    include($_SERVER['DOCUMENT_ROOT'].'/SolucionesWeb/Static/Controller/Sesion.php');
?>

<?php 
    class Recepcion {

        private $idRep;
        private $cantidadProducto;
        private $fecha;
        private $comentario;
        private $idProveedor;
        private $folio;
        private $conn;

        // Constructor
        public function __construct($conn) {
            $this->conn = $conn;
        }

        // Setters
        public function setIdRep($idRep) {
            $this->idRep = $idRep;
        }

        public function setCantidadProducto($cantidadProducto) {
            $this->cantidadProducto = $cantidadProducto;
        }

        public function setFecha($fecha) {
            $this->fecha = $fecha;
        }

        public function setComentario($comentario) {
            $this->comentario = $comentario;
        }

        public function setIdProveedor($idProveedor) {
            $this->idProveedor = $idProveedor;
        }

        public function setFolio($folio) {
            $this->folio = $folio;
        }

        // Getters
        public function getIdRep() {
            return $this->idRep;
        }

        public function getCantidadProducto() {
            return $this->cantidadProducto;
        }

        public function getFecha() {
            return $this->fecha;
        }

        public function getComentario() {
            return $this->comentario;
        }

        public function getIdProveedor() {
            return $this->idProveedor;
        }

        public function getFolio() {
            return $this->folio;
        }

        // Métodos para manejar registros de recepción
        public function obtenerRecepcion($idRep) {
            $sql = "SELECT * FROM recepcion WHERE idRep = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $idRep);
            $stmt->execute();
            $resultado = $stmt->get_result();
            return $resultado->fetch_assoc();
        }

        public function obtenerRecepciones() {
            // Consulta para obtener todas las recepciones
            $sql = "SELECT * FROM recepcion";
            $resultado = mysqli_query($this->conn, $sql);
            $recepciones = [];
            while ($fila = $resultado->fetch_assoc()) {
                $recepciones[] = $fila;
            }
            return $recepciones;
        }

        public function insertarRecepcion() {
            // Preparar la consulta de inserción
            $sql = "INSERT INTO recepcion (cantidadProducto, fecha, comentario, idProveedor, folio) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            if ($stmt) {
                // Asociar los parámetros de la consulta con los valores del objeto Recepcion
                $stmt->bind_param(
                    "issii",
                    $this->cantidadProducto,
                    $this->fecha,
                    $this->comentario,
                    $this->idProveedor,
                    $this->folio
                );
                // Ejecutar la consulta
                if ($stmt->execute()) {
                    header('Location: /SolucionesWeb/Static/View/Admin/ViewGestionRec.php'); 
                    exit;
                } else {
                    echo "Error al crear registro de recepción: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Error en la preparación de la consulta: " . $this->conn->error;
            }
        }

        public function eliminarRecepcion($idRep) {
            $sql = "DELETE FROM recepcion WHERE idRep = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $idRep);
        
            if ($stmt->execute()) {
                return true;
            } else {
                echo "Error al eliminar la recepción: " . $stmt->error;
                return false;
            }
        }
        
        //Modificar recepción
        public function modificarRecepcion() {
            // Preparar la consulta SQL
            $query = "UPDATE recepcion SET 
                        cantidadProducto = ?, 
                        fecha = ?, 
                        comentario = ?, 
                        idProveedor = ?, 
                        folio = ? 
                    WHERE idRep = ?";
        
            // Preparar la sentencia
            if ($stmt = $this->conn->prepare($query)) {
                // Obtener los datos del objeto Recepcion
                $stmt->bind_param(
                    "issiii",  // Tipo de los parámetros: string, string, string, entero, entero
                    $this->cantidadProducto,
                    $this->fecha,
                    $this->comentario,
                    $this->idProveedor,
                    $this->folio,
                    $this->idRep
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
        
        

        public function filtrarRecepcion($parametro) {
            // Preparar la consulta SQL para filtrar por comentario
            $sql = "SELECT * FROM recepcion WHERE folio LIKE ?";
            // Preparar la sentencia
            if ($stmt = $this->conn->prepare($sql)) {
                $parametro = '%' . $parametro . '%'; // Búsqueda con comodines
                $stmt->bind_param("i", $parametro);
                $stmt->execute();
                $resultado = $stmt->get_result();
                $recepcionesFiltradas = [];
                while ($fila = $resultado->fetch_assoc()) {
                    $recepcionesFiltradas[] = $fila;
                }
                return $recepcionesFiltradas;
            } else {
                echo "Error al preparar la consulta: " . $this->conn->error;
                return []; // Retornar un array vacío en caso de error
            }
        }    

    }
?>
