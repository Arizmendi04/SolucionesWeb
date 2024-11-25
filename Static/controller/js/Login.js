function validacionc() {
    // Obtener los campos del formulario
    const usuario = document.frm1.usuario;
    const contrasena = document.frm1.contrasena;

    // Obtener los elementos de alerta
    const errorUsuario = document.getElementById("errorUsuario");
    const errorContra = document.getElementById("errorContra");

    // Inicialmente, ocultar las alertas
    errorUsuario.style.display = "none";
    errorContra.style.display = "none";

    let valido = true;

    // Validar campo de usuario
    if (usuario.value.trim() === "") {
        errorUsuario.style.display = "block"; // Mostrar alerta
        usuario.focus();
        valido = false;
    }

    // Validar campo de contraseña
    if (contrasena.value.trim() === "") {
        errorContra.style.display = "block"; // Mostrar alerta
        contrasena.focus();
        valido = false;
    }

    // Retornar false si hay errores
    return valido;
}
