function validacionc() {
    // Obtener los campos del formulario
    const usuario = document.frm1.usuario;
    const contrasena = document.frm1.contrasena;
    
    // Validar campo de usuario
    if (usuario.value.length == 0) {
        document.getElementById("usuario").focus();
        alert("Por favor, ingresa tu usuario.");
        return false; // Evitar el envío del formulario
    }
    
    // Validar campo de contraseña
    if (contrasena.value.length == 0) {
        document.getElementById("contrasena").focus();
        alert("Por favor, ingresa tu contraseña.");
        return false; // Evitar el envío del formulario
    }

    // Si todos los campos son válidos, enviar el formulario
    frm1.submit();
}
