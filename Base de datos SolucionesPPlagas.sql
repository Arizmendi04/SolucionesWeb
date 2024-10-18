drop database if exists SoluPlagas;
create database SoluPlagas;
	use SoluPlagas;

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


