// confirmDelete.js
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('confirmModal');
    const confirmButton = document.getElementById('confirmDelete');
    const cancelButton = document.getElementById('cancelDelete');
    let deleteLink;

    // Mostrar el modal de confirmación al hacer clic en un enlace de eliminar
    document.querySelectorAll('.boton.eliminar').forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault(); // Prevenir la navegación
            deleteLink = this.href; // Guardar el enlace a eliminar
            modal.style.display = 'block'; // Mostrar el modal
        });
    });

    // Confirmar eliminación
    confirmButton.addEventListener('click', function () {
        window.location.href = deleteLink; // Redirigir a la eliminación
    });

    // Cancelar eliminación
    cancelButton.addEventListener('click', function () {
        console.log('Cancel button clicked'); // Verifica que el evento se esté ejecutando
        modal.style.display = 'none'; // Ocultar el modal
    });    
});

