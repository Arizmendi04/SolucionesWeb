<?php

    class Ticket {

        // Propiedades de la clase
        private $idNotaVenta;
        private $fecha;
        private $subtotal;
        private $iva;
        private $pagoTotal;
        private $estatus;
        private $noCliente;
        private $noEmpleado;
        private $conn;

        // Constructor
        public function __construct($conn) {
            $this->conn = $conn;
        }

        // Setters
        public function setIdNotaVenta($idNotaVenta) {
            $this->idNotaVenta = $idNotaVenta;
        }

        public function setFecha($fecha) {
            $this->fecha = $fecha;
        }

        public function setSubtotal($subtotal) {
            $this->subtotal = $subtotal;
        }

        public function setIva($iva) {
            $this->iva = $iva;
        }

        public function setPagoTotal($pagoTotal) {
            $this->pagoTotal = $pagoTotal;
        }

        public function setEstatus($estatus) {
            $this->estatus = $estatus;
        }

        public function setNoCliente($noCliente) {
            $this->noCliente = $noCliente;
        }

        public function setNoEmpleado($noEmpleado) {
            $this->noEmpleado = $noEmpleado;
        }

        // Getters
        public function getIdNotaVenta() {
            return $this->idNotaVenta;
        }

        public function getFecha() {
            return $this->fecha;
        }

        public function getSubtotal() {
            return $this->subtotal;
        }

        public function getIva() {
            return $this->iva;
        }

        public function getPagoTotal() {
            return $this->pagoTotal;
        }

        public function getEstatus() {
            return $this->estatus;
        }

        public function getNoCliente() {
            return $this->noCliente;
        }

        public function getNoEmpleado() {
            return $this->noEmpleado;
        }

        // Métodos para manejo de ticket
        public function obtenerTicket($idNotaVenta) {
            $sql = "SELECT * FROM notaVenta WHERE idNotaVenta = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $idNotaVenta);
            $stmt->execute();
            $resultado = $stmt->get_result();
            return $resultado->fetch_assoc();
        }

        public function obtenerDetallesTicket($ticketId) {
            $sql = "SELECT p.nombreProd AS producto, p.precio, d.cantidad, d.subtotal, prov.nombre AS proveedor 
                    FROM detalleventa d
                    JOIN producto p ON d.idProducto = p.idProducto
                    JOIN proveedor prov ON p.idProveedor = prov.idProveedor
                    WHERE d.idNotaVenta = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $ticketId);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }        

        public function obtenerTickets() {
            $sql = "SELECT * FROM notaVenta";
            $resultado = $this->conn->query($sql);
            $tickets = [];
            while ($fila = $resultado->fetch_assoc()) {
                $tickets[] = $fila;
            }
            return $tickets;
        }

        public function insertarTicket() {
            $sql = "INSERT INTO notaVenta (fecha, subtotal, iva, pagoTotal, estatus, noCliente, noEmpleado) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param(
                    "sdddsii",
                    $this->fecha,
                    $this->subtotal,
                    $this->iva,
                    $this->pagoTotal,
                    $this->estatus,
                    $this->noCliente,
                    $this->noEmpleado
                );
                if ($stmt->execute()) {
                    return true;
                } else {
                    echo "Error al insertar ticket: " . $stmt->error;
                    return false;
                }
                $stmt->close();
            } else {
                echo "Error en la preparación de la consulta: " . $this->conn->error;
                return false;
            }
        }

        public function eliminarTicket($idNotaVenta) {
            $sql = "DELETE FROM notaVenta WHERE idNotaVenta = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $idNotaVenta);
            return $stmt->execute();
        }

        public function modificarTicket() {
            $sql = "UPDATE notaVenta SET 
                        fecha = ?, 
                        subtotal = ?, 
                        iva = ?, 
                        pagoTotal = ?, 
                        estatus = ?, 
                        noCliente = ?, 
                        noEmpleado = ? 
                    WHERE idNotaVenta = ?";
            if ($stmt = $this->conn->prepare($sql)) {
                $stmt->bind_param(
                    "sdddsiii",
                    $this->fecha,
                    $this->subtotal,
                    $this->iva,
                    $this->pagoTotal,
                    $this->estatus,
                    $this->noCliente,
                    $this->noEmpleado,
                    $this->idNotaVenta
                );
                if ($stmt->execute()) {
                    return true;
                } else {
                    echo "Error al actualizar el ticket: " . $stmt->error;
                    return false;
                }
            } else {
                echo "Error al preparar la consulta: " . $this->conn->error;
                return false;
            }
        }
    }

?>
