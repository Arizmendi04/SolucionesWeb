// Validaciones.js
function validarNombre(nombre) {
    return nombre.length >= 2 && /^[a-zA-Z\s]+$/.test(nombre);
}

function validarApellido(apellido) {
    return apellido.length >= 2 && /^[a-zA-Z\s]+$/.test(apellido);
}

function validarFecha(fecha) {
    const partes = fecha.split("-");
    if (partes.length !== 3) return false;
    const año = parseInt(partes[0]);
    return /^\d{4}$/.test(partes[0]) && año >= 1900 && año <= new Date().getFullYear();
}

function validarSueldo(sueldo) {
    sueldo = parseFloat(sueldo);
    return !isNaN(sueldo) && sueldo > 0;
}

function validarCantidad(cantidad) {
    cantidad = parseInt(cantidad);
    return !isNaN(cantidad) && cantidad > 0;
}

function validarTelefono(telefono) {
    return /^\d{10}$/.test(telefono);
}

function validarCorreo(correo) {
    const expresionRegular = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return expresionRegular.test(correo);
}

function validacionEmpleado() {
    const nombre = document.getElementById("nombre").value.trim();
    const apellido = document.getElementById("apellido").value.trim();
    const fechaNacimiento = document.getElementById("fechaNac").value.trim();
    const fechaIngreso = document.getElementById("fechaIngreso").value.trim();
    const sueldo = document.getElementById("sueldo").value.trim();
    const telefono = document.getElementById("telefono").value.trim();

    if (!validarNombre(nombre)) {
        document.getElementById("errorNombre").style.display = "block";
        return false;
    } else {
        document.getElementById("errorNombre").style.display = "none";
    }

    if (!validarApellido(apellido)) {
        document.getElementById("errorApellido").style.display = "block";
        return false;
    } else {
        document.getElementById("errorApellido").style.display = "none";
    }

    if (!validarFecha(fechaNacimiento)) {
        document.getElementById("errorFechaNac").style.display = "block";
        return false;
    } else {
        document.getElementById("errorFechaNac").style.display = "none";
    }

    if (!validarFecha(fechaIngreso)) {
        document.getElementById("errorFechaIngreso").style.display = "block";
        return false;
    } else {
        document.getElementById("errorFechaIngreso").style.display = "none";
    }

    if (!validarSueldo(sueldo)) {
        document.getElementById("errorSueldo").style.display = "block";
        return false;
    } else {
        document.getElementById("errorSueldo").style.display = "none";
    }

    if (!validarTelefono(telefono)) {
        document.getElementById("errorTelefono").style.display = "block";
        return false;
    } else {
        document.getElementById("errorTelefono").style.display = "none";
    }

    return true;
}

function validacionModiEmpleado() {
    const sueldo = document.getElementById("sueldo").value.trim();
    const telefono = document.getElementById("telefono").value.trim();

    if (!validarSueldo(sueldo)) {
        document.getElementById("errorSueldo").style.display = "block";
        return false;
    } else {
        document.getElementById("errorSueldo").style.display = "none";
    }

    if (!validarTelefono(telefono)) {
        document.getElementById("errorTelefono").style.display = "block";
        return false;
    } else {
        document.getElementById("errorTelefono").style.display = "none";
    }

    return true;
}

function validacionProveedor() {
    const telefono = document.getElementById("telefono").value.trim();
    const correo = document.getElementById("correo").value.trim();

    if (!validarTelefono(telefono)) {
        document.getElementById("errorTelefono").style.display = "block";
        return false;
    } else {
        document.getElementById("errorTelefono").style.display = "none";
    }

    if (!validarCorreo(correo)) {
        document.getElementById("errorCorreo").style.display = "block";
        return false;
    } else {
        document.getElementById("errorCorreo").style.display = "none";
    }

    return true;
}

function validacionRecepcion() {
    const cantidad = document.getElementById("cantidadProducto").value.trim();
    const fecha = document.getElementById("fecha").value.trim();
    const idProveedor = document.getElementById("idProveedor").value.trim();
    const idProducto = document.getElementById("idProducto").value.trim(); 

    if (!validarCantidad(cantidad)) {
        document.getElementById("errorCantidad").style.display = "block";
        return false;
    } else {
        document.getElementById("errorCantidad").style.display = "none";
    }

    if (!validarFecha(fecha)) {
        document.getElementById("errorFecha").style.display = "block";
        return false;
    } else {
        document.getElementById("errorFecha").style.display = "none";
    }

     // Validación de ID de proveedor
     if (idProveedor === "") {
        document.getElementById("errorIdProveedor").style.display = "block";
        return false;
    } else {
        document.getElementById("errorIdProveedor").style.display = "none";
    }

    // Validación de ID de producto
    if (idProducto === "") {
        document.getElementById("errorIdProducto").style.display = "block";
        return false;
    } else {
        document.getElementById("errorIdProducto").style.display = "none";
    }
    
    return true;
}
