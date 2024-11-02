function validacion() {
    var nombre = document.getElementById("nombre").value.trim();
    var apellido = document.getElementById("apellido").value.trim();
    var fechaNacimiento = document.getElementById("fechaNac").value.trim(); // Actualizado para usar 'fechaNac'
    var fechaIngreso = document.getElementById("fechaIngreso").value.trim();
    var sueldo = document.getElementById("sueldo").value.trim();
    var telefono = document.getElementById("telefono").value.trim(); // Validación de teléfono

    var errorNombre = document.getElementById("errorNombre");
    var errorApellido = document.getElementById("errorApellido");
    var errorFechaNac = document.getElementById("errorFechaNac");
    var errorFechaIngreso = document.getElementById("errorFechaIngreso");
    var errorSueldo = document.getElementById("errorSueldo");
    var errorTelefono = document.getElementById("errorTelefono"); // Mensaje de error para teléfono

    // Validación de nombre
    if (nombre.length < 3 || !/^[a-zA-Z\s]+$/.test(nombre)) {
        errorNombre.style.display = "block"; 
        document.getElementById("nombre").focus();
        return false;
    } else {
        errorNombre.style.display = "none"; 
    }

    // Validación de apellido
    if (apellido.length < 3 || !/^[a-zA-Z\s]+$/.test(apellido)) {
        errorApellido.style.display = "block"; 
        document.getElementById("apellido").focus();
        return false;
    } else {
        errorApellido.style.display = "none"; 
    }

    // Validación de fecha de nacimiento
    if (!fechaNacimiento) {
        errorFechaNac.style.display = "block"; 
        document.getElementById("fechaNac").focus();
        return false;
    } else {
        errorFechaNac.style.display = "none"; 
    }

    // Validación de fecha de ingreso
    if (!fechaIngreso) {
        errorFechaIngreso.style.display = "block"; 
        document.getElementById("fechaIngreso").focus();
        return false;
    } else {
        errorFechaIngreso.style.display = "none"; 
    }

    // Validación de sueldo
    if (isNaN(sueldo) || sueldo <= 0) {
        errorSueldo.style.display = "block"; 
        document.getElementById("sueldo").focus();
        return false;
    } else {
        errorSueldo.style.display = "none"; 
    }

    // Validación de teléfono
    if (isNaN(telefono) || telefono.length < 10) { // Suponiendo que el teléfono debe tener al menos 10 dígitos
        errorTelefono.style.display = "block"; 
        document.getElementById("telefono").focus();
        return false;
    } else {
        errorTelefono.style.display = "none"; 
    }

    // Si todas las validaciones son correctas, permite el envío del formulario
    return true;
}
