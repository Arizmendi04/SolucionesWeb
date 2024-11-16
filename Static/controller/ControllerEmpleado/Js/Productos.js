// Función para filtrar productos en tiempo real
function filtrarProductos(valor) {
    const cards = document.querySelectorAll('.product-card');
    valor = valor.toLowerCase();

    cards.forEach(card => {
        const nombre = card.querySelector('h5').textContent.toLowerCase();
        card.style.display = nombre.includes(valor) ? "block" : "none";
    });
}

// Evento para el input de proveedores
const inputProveedor = document.getElementById("proveedorNombre"); // Cambié a 'proveedorNombre' para obtener el campo correcto
inputProveedor.addEventListener("input", function() {
    buscarProveedor(this.value);
});

// Evento para mostrar todos los proveedores al hacer clic en el input
inputProveedor.addEventListener("click", function() {
    const listaProveedores = document.getElementById("listaProveedores");
    listaProveedores.style.display = "block"; // Mostrar lista al hacer clic
    buscarProveedor(''); // Cargar todos los proveedores
});

// Función para buscar proveedores
function buscarProveedor(nombreProveedor) {
    const listaProveedores = document.getElementById("listaProveedores");
    const xhr = new XMLHttpRequest();
    
    // Siempre se llama a la API para obtener todos los proveedores si no hay valor
    xhr.open("GET", `/SolucionesWeb/Static/Controller/ControllerEmpleado/Proveedores.php?accion=filtrar&nombre=${nombreProveedor}`, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            listaProveedores.innerHTML = xhr.responseText;
            listaProveedores.style.display = "block"; // Mostrar lista
        }
    };
    xhr.send();
}

// Función para seleccionar un proveedor de la lista
function seleccionarProveedor(idProveedor, nombreProveedor) {
    document.getElementById("proveedorNombre").value = nombreProveedor; // Solo mostrar el nombre
    document.getElementById("idProveedor").value = idProveedor; // Guardar el ID en un campo oculto
    document.getElementById("listaProveedores").innerHTML = ""; // Limpiar la lista
    document.getElementById("listaProveedores").style.display = "none"; // Ocultar lista después de seleccionar
}

// Evento para cerrar la lista de proveedores al hacer clic fuera
document.addEventListener("click", function(event) {
    const listaProveedores = document.getElementById("listaProveedores");
    if (!event.target.closest('#proveedorNombre') && !event.target.closest('#listaProveedores')) {
        listaProveedores.style.display = "none"; // Ocultar la lista
    }
});

// Manejar el cambio de la imagen
document.getElementById("fotoProducto").addEventListener("change", function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById("previewImg");
    const removePreview = document.getElementById("removePreview");
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            removePreview.style.display = "flex"; // Muestra la "X"
        };
        reader.readAsDataURL(file);
    } else {
        preview.src = "/SolucionesWeb/Static/Img/imagengenerica.png"; // Imagen predeterminada
        removePreview.style.display = "none"; // Oculta la "X"
    }
});

// Funcionalidad para la "X" de eliminación
document.getElementById("removePreview").addEventListener("click", function() {
    const preview = document.getElementById("previewImg");
    const removePreview = document.getElementById("removePreview");

    preview.src = "/SolucionesWeb/Static/Img/imagengenerica.png"; // Imagen predeterminada
    document.getElementById("fotoProducto").value = ""; // Limpia el campo de archivo
    removePreview.style.display = "none"; // Oculta la "X"
});
