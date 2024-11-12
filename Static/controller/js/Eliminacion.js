// confirmDelete.js
document.addEventListener('DOMContentLoaded', function () {
    const modal1 = document.getElementById('confirmModal');
    const confirmButton = document.getElementById('confirmDelete');
    const cancelButton = document.getElementById('cancelDelete');
    let deleteLink;

    // Mostrar el modal de confirmación al hacer clic en un enlace de eliminar
    document.querySelectorAll('.boton.eliminar').forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault(); // Prevenir la navegación
            deleteLink = this.href; // Guardar el enlace a eliminar
            modal1.style.display = 'block'; // Mostrar el modal
        });
    });

    // Confirmar eliminación
    confirmButton.addEventListener('click', function () {
        window.location.href = deleteLink; // Redirigir a la eliminación
    });

    // Cancelar eliminación
    cancelButton.addEventListener('click', function () {
        console.log('Cancel button clicked'); // Verifica que el evento se esté ejecutando
        modal1.style.display = 'none'; // Ocultar el modal
    });    
});

    function actualizarCampos() {
        const cliente = document.getElementById('noCliente').value;
        const empleado = document.getElementById('noEmpleado').value;

        document.getElementById('noClienteHidden').value = cliente;
        document.getElementById('noEmpleadoHidden').value = empleado;
    }
    function validateFinalizar(event) {
        const cliente = document.getElementById('noClienteHidden').value;
        const empleado = document.getElementById('noEmpleadoHidden').value;
        
        // Verificar si el cliente y el empleado están seleccionados
        if (!cliente || !empleado) {
            showModal("Por favor, selecciona un cliente y un empleado.");
            event.preventDefault();  // Evitar el envío del formulario
            return false;  // No enviar el formulario
        }
        
        // Verificar si hay productos en la venta
        const rows = document.getElementById('tablaVentas').getElementsByTagName('tbody')[0].rows;
        let tieneProductos = false;
        for (let i = 0; i < rows.length; i++) {
            if (rows[i].cells[1] && rows[i].cells[1].innerText !== "No hay productos en la venta actual") {
                tieneProductos = true;
                break;  // Si hay productos, continuar
            }
        }
        
        // Si no hay productos, mostrar el modal y evitar el envío del formulario
        if (!tieneProductos) {
            showModal("Por favor, agrega productos antes de continuar con la compra.");
            event.preventDefault();  // Evitar el envío del formulario
            return false;  // No enviar el formulario
        }
        
        return true;  // Permitir la finalización si todo está correcto
    }
    
    // Función para mostrar el modal
    function showModal(message) {
        const modal = document.getElementById('modalAdvertencia');
        const modalBody = document.querySelector('#modalAdvertencia .modalBody');
        modalBody.innerHTML = message; // Actualizar el mensaje del modal
        modal.style.display = 'flex'; // Mostrar el modal
    }
    
    // Función para cerrar el modal
    document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('modalAdvertencia').style.display = 'none'; // Ocultar el modal
    });
    
    document.getElementById('closeModalButton').addEventListener('click', function() {
        document.getElementById('modalAdvertencia').style.display = 'none'; // Ocultar el modal
    });
    