drop database if exists danielt2_plagas2024;
create database danielt2_plagas2024;
	use danielt2_plagas2024;

create table proveedor(
	idProveedor int(4) zerofill primary key auto_increment,
    razonSocial text not null,
    nombreComercial varchar(100) not null,
    telefono varchar(60),
    correo varchar(60)
);    

create table producto(
	folio int(4) zerofill primary key auto_increment,
	nombreProd varchar(60),
    tipo varchar(100) not null,
    unidadM varchar(15) not null,
    existencia int not null,
	peso float not null,
    descripcion text,
    precio float not null,
    urlImagen varchar(100) not null,
    idProveedor int unsigned,
    foreign key(idProveedor) references proveedor(idProveedor) on delete set null on update cascade
);

create table empleado(
	noEmpleado int(4) zerofill primary key auto_increment,
    nombre varchar(60),
    apellido varchar(60),
    sexo varchar(60),
    fechaNac date,
    fechaIngreso date,
    sueldo float, 
    cargo varchar(60),
    telefono varchar(60),
    direccion varchar(60),
	urlFotoPerfil varchar(100)
);

create table cliente(
	noCliente int(4) zerofill primary key auto_increment,
    clienteRFC varchar(40) not null,
    nombreC varchar(100) not null,
    razonSocial varchar(100) not null,
    email varchar(50) not null,
    telefonoC varchar(10) not null,
	calle varchar(240) not null,
	colonia varchar(50) not null,
    localidad varchar(50) not null,
    municipio varchar(50) not null,
    estado varchar(50) not null,
    clienteCP int not null
);

create table notaVenta(
	idNotaVenta int(4) zerofill primary key auto_increment,
    fecha date not null, 
	subtotal float not null,
    iva float not null,
    pagoTotal float not null,
    estatus varchar(15) not null,
    noCliente int unsigned,
    noEmpleado int unsigned,
    foreign key(noCliente) references cliente (noCliente) on delete set null on update cascade,
    foreign key(noEmpleado) references empleado (noEmpleado) on delete set null on update cascade
);

create table venta(
	idVenta int(4) zerofill primary key auto_increment,
    cantidad int not null,
	total float not null,
    folio int unsigned,
    idNotaVenta int unsigned,
    foreign key(folio) references producto (folio) on delete set null on update cascade,
    foreign key(idNotaVenta) references notaVenta (idNotaVenta) on delete set null on update cascade
);

create table recepcion(
	idRep int(4) zerofill primary key auto_increment,
    cantidadProducto int not null,
    fecha date not null,
    comentario varchar(100),
    idProveedor int unsigned,
    folio int unsigned,
    foreign key(folio) references producto (folio) on delete set null on update cascade,
    foreign key(idProveedor) references proveedor (idProveedor) on delete set null on update cascade
);

create table usuario(
    id int(4) zerofill primary key auto_increment,
    nombreU varchar(50) not null,
    contrasena varchar(50) not null,
    tipoU varchar(50) not null,
    noEmpleado int unsigned,
    foreign key(noEmpleado) references empleado (noEmpleado) on delete set null on update cascade
);

-- Ingresar datos en la tabla proveedor
INSERT INTO proveedor (razonSocial, nombreComercial, telefono, correo) VALUES
('Proveedor A S.A. de C.V.', 'ProveA', '555-1234', 'contacto@provea.com'),
('Proveedor B S.A. de C.V.', 'ProveB', '555-5678', 'contacto@proveb.com');

-- Ingresar datos en la tabla producto
INSERT INTO producto (nombreProd, tipo, unidadM, existencia, peso, descripcion, precio, urlImagen, idProveedor) VALUES
('Insecticida A', 'Insecticida', 'litro', 50, 1.5, 'Insecticida eficaz contra plagas.', 200.00, 'url_de_imagen_a.jpg', 1),
('Herbicida B', 'Herbicida', 'litro', 30, 1.0, 'Herbicida para control de malezas.', 150.00, 'url_de_imagen_b.jpg', 1),
('Fertilizante C', 'Fertilizante', 'kilogramo', 20, 2.0, 'Fertilizante de liberación lenta.', 300.00, 'url_de_imagen_c.jpg', 2);

-- Ingresar datos en la tabla empleado
INSERT INTO empleado (nombre, apellido, sexo, fechaNac, fechaIngreso, sueldo, cargo, telefono, direccion, urlFotoPerfil) VALUES
('Juan', 'Pérez', 'Masculino', '1990-01-15', '2020-05-01', 12000.00, 'Vendedor', '555-1111', 'Calle Falsa 123', 'url_foto_juan.jpg'),
('María', 'González', 'Femenino', '1985-06-20', '2018-03-15', 14000.00, 'Administradora', '555-2222', 'Avenida Siempre Viva 456', 'url_foto_maria.jpg');

-- Ingresar datos en la tabla cliente
INSERT INTO cliente (clienteRFC, nombreC, razonSocial, email, telefonoC, calle, colonia, localidad, municipio, estado, clienteCP) VALUES
('RFC123456789', 'Carlos Mendoza', 'Mendoza S.A. de C.V.', 'carlos@ejemplo.com', '555-3333', 'Calle Ejemplo 789', 'Centro', 'Ejemplo', 'Ejemplo', 'Estado Ejemplo', 12345),
('RFC987654321', 'Ana López', 'López S.A. de C.V.', 'ana@ejemplo.com', '555-4444', 'Calle Prueba 321', 'Centro', 'Prueba', 'Ejemplo', 'Estado Prueba', 54321);

-- Ingresar datos en la tabla notaVenta
INSERT INTO notaVenta (fecha, subtotal, iva, pagoTotal, estatus, noCliente, noEmpleado) VALUES
('2024-01-01', 1000.00, 160.00, 1160.00, 'Pagado', 1, 1),
('2024-02-01', 500.00, 80.00, 580.00, 'Pendiente', 2, 2);

-- Ingresar datos en la tabla venta
INSERT INTO venta (cantidad, total, folio, idNotaVenta) VALUES
(5, 1000.00, 1, 1),
(3, 450.00, 2, 2);

-- Ingresar datos en la tabla recepcion
INSERT INTO recepcion (cantidadProducto, fecha, comentario, idProveedor, folio) VALUES
(20, '2024-01-10', 'Recepción inicial de productos.', 1, 1),
(10, '2024-01-15', 'Segunda recepción de productos.', 2, 2);

-- Ingresar datos en la tabla usuario
INSERT INTO usuario (nombreU, contrasena, tipoU, noEmpleado) VALUES
('admin', 'admin123', 'admin', 1),
('user', 'user123', 'usuario', 2);




