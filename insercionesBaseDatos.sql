-- Ingresar datos en la tabla proveedor
INSERT INTO proveedor (razonSocial, nombreComercial, telefono, correo) VALUES
('Proveedor A S.A. de C.V.', 'ProveA', '555-1234', 'contacto@provea.com'),
('Proveedor B S.A. de C.V.', 'ProveB', '555-5678', 'contacto@proveb.com');

-- Ingresar datos en la tabla cliente
INSERT INTO cliente (clienteRFC, nombreC, razonSocial, email, telefonoC, calle, colonia, localidad, municipio, estado, clienteCP) VALUES
('RFC123456789', 'Carlos Mendoza', 'Mendoza S.A. de C.V.', 'carlos@mendoza.com', '555-3333', 'Calle Reforma 789', 'Centro', 'Ciudad de México', 'Cuauhtémoc', 'Ciudad de México', 12345),
('RFC987654321', 'Ana López', 'López & Asociados', 'ana@lopez.com', '555-4444', 'Calle Insurgentes 321', 'Roma', 'Ciudad de México', 'Cuauhtémoc', 'Ciudad de México', 54321),
('RFC456789123', 'Luis Hernández', 'Hernández Consultores', 'luis@hernandez.com', '555-5555', 'Avenida Juárez 15', 'Centro', 'Ciudad de México', 'Benito Juárez', 'Ciudad de México', 11223),
('RFC112233445', 'María Fernández', 'Fernández Distribuciones', 'maria@fernandez.com', '555-6666', 'Calle Universidad 50', 'Centro', 'Guadalajara', 'Guadalajara', 'Jalisco', 22000),
('RFC998877665', 'Pedro Gómez', 'Gómez Hermanos', 'pedro@gomez.com', '555-7777', 'Calle Vallarta 200', 'Zapopan', 'Zapopan', 'Guadalajara', 'Jalisco', 22500),
('RFC334455667', 'Sofía Ramírez', 'Ramírez y Compañía', 'sofia@ramirez.com', '555-8888', 'Calle López Mateos 250', 'Centro', 'Guadalajara', 'Guadalajara', 'Jalisco', 23000),
('RFC556677889', 'Alejandro Castillo', 'Castillo Arquitectos', 'alejandro@castillo.com', '555-9999', 'Avenida Revolución 300', 'Centro', 'Monterrey', 'Monterrey', 'Nuevo León', 33210),
('RFC667788990', 'Lucía Morales', 'Morales SA de CV', 'lucia@morales.com', '555-0000', 'Calle Constitución 350', 'Centro', 'Monterrey', 'Monterrey', 'Nuevo León', 33220),
('RFC998877554', 'Ricardo Rivera', 'Rivera Servicios', 'ricardo@rivera.com', '555-1111', 'Calle Hidalgo 25', 'Centro', 'Cancún', 'Benito Juárez', 'Quintana Roo', 77500),
('RFC887766554', 'Gabriela Flores', 'Flores & Asociados', 'gabriela@flores.com', '555-2222', 'Avenida Tulum 500', 'Zona Hotelera', 'Cancún', 'Benito Juárez', 'Quintana Roo', 77550),
('RFC776655443', 'José Pérez', 'Pérez Industrial', 'jose@perez.com', '555-3333', 'Calle Corregidora 78', 'Centro', 'Querétaro', 'Querétaro', 'Querétaro', 76000),
('RFC665544332', 'Raquel Gómez', 'Gómez y Cía.', 'raquel@gomez.com', '555-4444', 'Avenida Zaragoza 90', 'Centro', 'Querétaro', 'Querétaro', 'Querétaro', 76010),
('RFC554433221', 'Patricia Ortiz', 'Ortiz Transportes', 'patricia@ortiz.com', '555-5555', 'Calle Hidalgo 34', 'San Miguel', 'Querétaro', 'Querétaro', 'Querétaro', 76020);


-- Ingresar datos en la tabla producto
INSERT INTO producto (nombreProd, tipo, unidadM, existencia, peso, descripcion, precio, urlImagen, idProveedor) VALUES
('Insecticida A', 'Insecticida', 'litros', 50, 1.5, 'Insecticida eficaz contra plagas.', 200.00, 'productos.png', 1),
('Herbicida B', 'Herbicida', 'litros', 30, 1.0, 'Herbicida para control de malezas.', 150.00, 'productos.png', 1),
('Fertilizante C', 'Fertilizante', 'kilogramos', 20, 2.0, 'Fertilizante de liberación lenta.', 300.00, 'productos.png', 2),
('Insecticida D', 'Insecticida', 'litros', 60, 1.2, 'Insecticida orgánico para cultivos.', 180.00, 'productos.png', 2),
('Insecticida E', 'Insecticida', 'kilogramos', 40, 0.8, 'Insecticida granulado para uso agrícola.', 220.00, 'productos.png', 1),
('Insecticida F', 'Insecticida', 'kilogramos', 40, 0.8, 'Insecticida granulado ', 100.00, 'productos.png', 1),
('Insecticida G', 'Insecticida', 'kilogramos', 10, 0.8, 'Insecticida complwto ', 320.00, 'productos.png', 2);

-- Ingresar datos en la tabla recepcion
INSERT INTO recepcion (cantidadProducto, fecha, comentario, idProveedor, folio) VALUES
(20, '2024-01-10', 'Recepción inicial de productos.', 1, 1),
(10, '2024-01-15', 'Segunda recepción de productos.', 2, 2);

-- Ingresar datos en la tabla empleado
INSERT INTO empleado (nombre, apellido, sexo, fechaNac, fechaIngreso, sueldo, cargo, telefono, direccion) VALUES
('María', 'González', 'Femenino', '1985-06-20', '2018-03-15', 14000.00, 'Gerente', '555-2222', 'Avenida Cuauhtemoc 456'),
('Yatziry', 'Serrano', 'Femenino', '2004-01-24', '2024-04-10', 32000.00, 'Limpieza', '7774931305', 'Calle Privada Chilpancingo #20');

-- Ingresar datos en la tabla notaVenta
INSERT INTO notaVenta (fecha, subtotal, iva, pagoTotal, estatus, noCliente, noEmpleado) VALUES
('2024-01-01', 1000.00, 160.00, 1160.00, 'Pagado', 1, 1),
('2024-02-01', 500.00, 80.00, 580.00, 'Pagado', 2, 2);

-- Ingresar datos en la tabla venta
INSERT INTO venta (cantidad, total, folio, idNotaVenta) VALUES
(5, 1000.00, 1, 1),
(3, 450.00, 2, 2);


select *from usuario;
select *from empleado;
select *from recepcion;
select *from producto;
select *from cliente;

