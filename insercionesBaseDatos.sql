-- Ingresar datos en la tabla proveedor
INSERT INTO proveedor (razonSocial, nombreComercial, telefono, correo) VALUES
('Proveedor A S.A. de C.V.', 'ProveA', '555-1234', 'contacto@provea.com'),
('Proveedor B S.A. de C.V.', 'ProveB', '555-5678', 'contacto@proveb.com');

-- Ingresar datos en la tabla cliente
INSERT INTO cliente (clienteRFC, nombreC, razonSocial, email, telefonoC, calle, colonia, localidad, municipio, estado, clienteCP) VALUES
('RFC123456789', 'Carlos Mendoza', 'Mendoza S.A. de C.V.', 'carlos@ejemplo.com', '555-3333', 'Calle Ejemplo 789', 'Centro', 'Ejemplo', 'Ejemplo', 'Estado Ejemplo', 12345),
('RFC987654321', 'Ana López', 'López S.A. de C.V.', 'ana@ejemplo.com', '555-4444', 'Calle Prueba 321', 'Centro', 'Prueba', 'Ejemplo', 'Estado Prueba', 54321);

-- Ingresar datos en la tabla producto
INSERT INTO producto (nombreProd, tipo, unidadM, existencia, peso, descripcion, precio, urlImagen, idProveedor) VALUES
('Insecticida A', 'Insecticida', 'litro', 50, 1.5, 'Insecticida eficaz contra plagas.', 200.00, 'productos.png', 1),
('Herbicida B', 'Herbicida', 'litro', 30, 1.0, 'Herbicida para control de malezas.', 150.00, 'productos.png', 1),
('Fertilizante C', 'Fertilizante', 'kilogramo', 20, 2.0, 'Fertilizante de liberación lenta.', 300.00, 'productos.png', 2);

-- Ingresar datos en la tabla recepcion
INSERT INTO recepcion (cantidadProducto, fecha, comentario, idProveedor, folio) VALUES
(20, '2024-01-10', 'Recepción inicial de productos.', 1, 1),
(10, '2024-01-15', 'Segunda recepción de productos.', 2, 2);
-- Ingresar datos en la tabla venta
INSERT INTO venta (cantidad, total, folio, idNotaVenta) VALUES
(5, 1000.00, 1, 1),
(3, 450.00, 2, 2);

-- Ingresar datos en la tabla empleado
INSERT INTO empleado (nombre, apellido, sexo, fechaNac, fechaIngreso, sueldo, cargo, telefono, direccion, urlFotoPerfil) VALUES
('María', 'González', 'Femenino', '1985-06-20', '2018-03-15', 14000.00, 'Gerente', '555-2222', 'Avenida Cuauhtemoc 456', 'url_foto_maria.jpg'),
('Yatziry', 'Serrano', 'Femenino', '2004-01-24', '2024-04-10', 32000.00, 'Limpieza', '7774931305', 'Calle Privada Chilpancingo #20', 'url_foto_yatziry.jpg');

-- Ingresar datos en la tabla notaVenta
INSERT INTO notaVenta (fecha, subtotal, iva, pagoTotal, estatus, noCliente, noEmpleado) VALUES
('2024-01-01', 1000.00, 160.00, 1160.00, 'Pagado', 1, 1),
('2024-02-01', 500.00, 80.00, 580.00, 'Pendiente', 2, 2);

select *from usuario;
select *from empleado;
select *from recepcion;
select *from producto;