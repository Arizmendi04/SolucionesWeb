document.getElementById('agregarCarrito').addEventListener('click', function(event) {
    event.preventDefault(); // Prevenimos el comportamiento por defecto del enlace (que redirige)

    var folio = this.getAttribute('href').split('folio=')[1].split('&')[0];
    var cantidad = this.getAttribute('href').split('cantidad=')[1];

    // Realizamos la solicitud AJAX al archivo PHP
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "agregaralcarrito.php?folio=" + folio + "&cantidad=" + cantidad, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert(response.message); // Mostrar mensaje de éxito
            } else {
                alert(response.message); // Mostrar mensaje de error
            }
        }
    };
    xhr.send();
});

function mostrarMensajeExito(mensaje) {
    const mensajeExito = document.getElementById("mensajeExito");
    const mensajeTexto = document.getElementById("mensajeTexto");
    
    mensajeTexto.textContent = mensaje;
    mensajeExito.style.display = "block";
    
    // Ocultar el mensaje después de unos segundos
    setTimeout(() => {
        mensajeExito.style.display = "none";
    }, 3000);
}