// Seleccionamos todos los botones con el id 'agregarCarrito' y los recorremos
document.querySelectorAll('#agregarCarrito').forEach(button => {
    button.addEventListener('click', function (event) {
        event.preventDefault(); // Evitamos que el enlace recargue la página por defecto
        const url = this.getAttribute('href'); // Obtenemos la URL del atributo 'href' del botón

        // Realizamos la solicitud a la URL obtenida
        fetch(url)
            .then(response => response.json()) // Convertimos la respuesta a JSON
            .then(data => {
                if (data.success) {
                    mostrarMensaje('Producto agregado al carrito con éxito.');
                } else {
                    mostrarMensaje(data.message || 'Error al agregar el producto al carrito.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarMensaje('Hubo un problema con la solicitud.', 'error');
            });
    });
});

function mostrarMensaje(mensaje, tipo = 'success') {
    const mensajeExito = document.getElementById('mensajeExito'); // Seleccionamos el contenedor del mensaje
    const mensajeTexto = document.getElementById('mensajeTexto');

    mensajeTexto.textContent = mensaje;
    if (tipo === 'error') {
        mensajeExito.classList.remove('alert-success');
        mensajeExito.classList.add('alert-danger');
    } else {
        mensajeExito.classList.remove('alert-danger');
        mensajeExito.classList.add('alert-success');
    }

    mensajeExito.style.display = 'block';
    setTimeout(() => {
        mensajeExito.style.display = 'none';
    }, 3000); // Ocultar el mensaje después de 3 segundos
}
