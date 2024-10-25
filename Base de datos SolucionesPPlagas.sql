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
    nombre varchar(60) not null,
    apellido varchar(60) not null,
    sexo varchar(60),
    fechaNac date not null,
    fechaIngreso date not null,
    sueldo float not null, 
    cargo varchar(60) not null,
    telefono varchar(60) not null,
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

/*Ticket*/
create table notaVenta(
	idNotaVenta int(4) zerofill primary key auto_increment,
    fecha date not null, 
	subtotal float not null, /*suma de todos los totales de la venta pequeña*/
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
	total float not null, /*total de la venta pequeña*/
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

-- Ingresar datos en la tabla cliente
INSERT INTO cliente (clienteRFC, nombreC, razonSocial, email, telefonoC, calle, colonia, localidad, municipio, estado, clienteCP) VALUES
('RFC123456789', 'Carlos Mendoza', 'Mendoza S.A. de C.V.', 'carlos@ejemplo.com', '555-3333', 'Calle Ejemplo 789', 'Centro', 'Ejemplo', 'Ejemplo', 'Estado Ejemplo', 12345),
('RFC987654321', 'Ana López', 'López S.A. de C.V.', 'ana@ejemplo.com', '555-4444', 'Calle Prueba 321', 'Centro', 'Prueba', 'Ejemplo', 'Estado Prueba', 54321);

-- Ingresar datos en la tabla notaVenta
INSERT INTO notaVenta (fecha, subtotal, iva, pagoTotal, estatus, noCliente, noEmpleado) VALUES
('2024-01-01', 1000.00, 160.00, 1160.00, 'Pagado', 1, 1),
('2024-02-01', 500.00, 80.00, 580.00, 'Pendiente', 2, 2);

-- Ingresar datos en la tabla usuario
INSERT INTO usuario (nombreU, contrasena, tipoU, noEmpleado) VALUES
('admin', 'admin123', 'admin', 1),
('user', 'user123', 'usuario', 2);


/*DISPARADORES*/
/*Disparador que aumenta las existencias de los productos después de que se haya
registrado una recepcion*/
drop trigger if exists aumentarExisProd;
delimiter //
create trigger aumentarExisProd after insert on recepcion
for each row
begin
  update producto set existencia = existencia + new.cantidadproducto
  where folio = new.folio;
end //

-- Ingresar datos en la tabla recepcion
INSERT INTO recepcion (cantidadProducto, fecha, comentario, idProveedor, folio) VALUES
(20, '2024-01-10', 'Recepción inicial de productos.', 1, 1),
(10, '2024-01-15', 'Segunda recepción de productos.', 2, 2);
select *from producto;

/*Disparador que disminuye las existencias de los productos después de que se haya
registrado una venta*/
drop trigger if exists disminuirExisProd;
delimiter //
create trigger disminuirExisProd after insert on venta
for each row
begin
  update producto set existencia = existencia - new.cantidad
  where folio = new.folio;
end //

-- Ingresar datos en la tabla venta
INSERT INTO venta (cantidad, total, folio, idNotaVenta) VALUES
(5, 1000.00, 1, 1),
(3, 450.00, 2, 2);

select *from producto;

/*Disparador para crearle un usuario a un nuevo empleado que esté siendo registrado. 
El usuario se creará de la siguiente manera: Primera letra del nombre en minúscula, El resto del nombre,
Primera letra del apellido en mayúscula, El resto del apellido, Primera letra del cargo en mayúscula,
El resto del cargo, Número secuencial basado en el ID del empleado. Además, se les creará una contraseña
automática:   Primera letra del nombre + primera letra del apellido + 4 números aleatorios + símbolo "!"
*/

drop trigger if exists generar_usuario_empleado;
delimiter //
create trigger generar_usuario_empleado
after insert on empleado
for each row
begin
  declare nuevo_usuario varchar(100);
  declare nueva_contrasena varchar(20);

  -- Verificar si el cargo del empleado ES uno de los permitidos
  if lower(new.cargo) in ('administrador', 'vendedor', 'soporte tecnico', 'gerente') then
    
    -- Generar el nombre de usuario en notación camello
    set nuevo_usuario = lower(concat(
        substring(new.nombre, 1, 3), 
        substring(new.apellido, 1, 3),  
        substring(new.cargo, 1, 3),     
        lpad(new.noempleado, 3, '0')    
    ));
    
    -- Generar la contraseña automática
    set nueva_contrasena = concat(
      substring(new.nombre, 1, 1), 
      substring(new.apellido, 1, 1), 
      lpad(floor(rand() * 10000), 4, '0'),
      '@' 
    );
    
    -- Insertar en la tabla usuario
    insert into usuario (nombreu, contrasena, tipou, noempleado)
    values (nuevo_usuario, nueva_contrasena, 'Empleado', new.noempleado);
  
  end if;
  
end//

/*Disparador para evitar empleados duplicados*/s
drop trigger if exists verificar_empleado_duplicado;
delimiter //
create trigger verificar_empleado_duplicado
before insert on empleado
for each row
begin
    -- verificar si ya existe un empleado con el mismo nombre, apellido, fecha de nacimiento, correo o teléfono
    if exists (
        select 1 
        from empleado 
        where nombre = new.nombre
          and apellido = new.apellido
          and fechanac = new.fechanac
          and telefono = new.telefono
    ) then
        signal sqlstate '45000'
        set message_text = 'el empleado ya está registrado con el mismo nombre, apellido, fecha de nacimiento o teléfono';
    end if;
end //


-- Ingresar datos en la tabla empleado
INSERT INTO empleado (nombre, apellido, sexo, fechaNac, fechaIngreso, sueldo, cargo, telefono, direccion, urlFotoPerfil) VALUES
('Juan', 'Pérez', 'Masculino', '1990-01-15', '2020-05-01', 12000.00, 'Vendedor', '555-1111', 'Calle Falsa 123', 'url_foto_juan.jpg'),
('María', 'González', 'Femenino', '1985-06-20', '2018-03-15', 14000.00, 'Administrador', '555-2222', 'Avenida Siempre Viva 456', 'url_foto_maria.jpg'),
('Yatziry', 'Serrano', 'Femenino', '2004-01-24', '2024-04-10', 32000.00, 'Limpieza', '7774931305', 'Calle Privada Chilpancingo #20', 'url_foto_yatziry.jpg');



