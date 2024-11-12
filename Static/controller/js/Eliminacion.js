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

// Actualizar los campos ocultos con los valores seleccionados
function actualizarCampos() {
    const cliente = document.getElementById('noCliente').value;
    const empleado = document.getElementById('noEmpleado').value;

    document.getElementById('noClienteHidden').value = cliente;
    document.getElementById('noEmpleadoHidden').value = empleado;
}

// Validación para asegurarse de que el cliente y empleado estén seleccionados y haya productos
function validateFinalizar(event) {
    const cliente = document.getElementById('noClienteHidden').value;
    const empleado = document.getElementById('noEmpleadoHidden').value;
    
    // Comprobar si el cliente y el empleado están seleccionados
    if (!cliente || !empleado) {
        const modal = new bootstrap.Modal(document.getElementById('modalAdvertencia'));
        document.querySelector('#modalAdvertencia .modal-body').innerHTML = "Por favor, selecciona un cliente y un empleado.";
        modal.show();
        event.preventDefault();  // Prevenir el envío del formulario
        return false;
    }

    // Verificar si hay productos en la venta
    const rows = document.getElementById('tablaVentas').getElementsByTagName('tbody')[0].rows;
    let tieneProductos = false;
    // Iterar solo sobre las filas del cuerpo (excluyendo encabezados)
    for (let i = 0; i < rows.length; i++) {
        // Asegurarnos de que la fila contiene productos y no el mensaje de "No hay productos en la venta actual"
        if (rows[i].cells[1] && rows[i].cells[1].innerText !== "No hay productos en la venta actual") {
            tieneProductos = true;
            break; // Salir del bucle si encontramos un producto válido
        }
    }

    // Si no hay productos, mostrar el modal y evitar el envío del formulario
    if (!tieneProductos) {
        const modal = new bootstrap.Modal(document.getElementById('modalAdvertencia'));
        modal.show();  // Abrir el modal
        event.preventDefault();  // Evitar el envío del formulario
        return false;  // Impedir que se continúe con la finalización
    }

    return true; // Permitir la finalización si hay productos
}

// Agregar el event listener al formulario de finalizar compra
document.querySelector('form').addEventListener('submit', validateFinalizar);
