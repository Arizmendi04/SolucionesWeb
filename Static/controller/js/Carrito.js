document.querySelectorAll('#agregarCarrito').forEach(button => {
    button.addEventListener('click', function (event) {
        event.preventDefault();
        const url = this.getAttribute('href');

        fetch(url)
            .then(response => response.json())
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
    const mensajeExito = document.getElementById('mensajeExito');
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
