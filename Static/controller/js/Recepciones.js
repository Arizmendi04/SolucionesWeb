// Función para filtrar recepciones en tiempo real
function filtrarRecepciones(valor) {
    const cards = document.querySelectorAll('.recepcion-card');
    valor = valor.toLowerCase();

    cards.forEach(card => {
        const nombre = card.querySelector('h5').textContent.toLowerCase();
        card.style.display = nombre.includes(valor) ? "block" : "none";
    });
}

// Evento para el input de proveedores
const inputProveedor = document.getElementById("proveedorNombre");
inputProveedor.addEventListener("input", function() {
    buscarProveedor(this.value);
});

// Evento para mostrar todos los proveedores al hacer clic en el input
inputProveedor.addEventListener("click", function() {
    const listaProveedores = document.getElementById("listaProveedores");
    listaProveedores.style.display = "block";
    buscarProveedor(''); // Cargar todos los proveedores
});

// Función para buscar proveedores
function buscarProveedor(nombreProveedor) {
    const listaProveedores = document.getElementById("listaProveedores");
    const xhr = new XMLHttpRequest();

    xhr.open("GET", `/SolucionesWeb/Static/Controller/Proveedores.php?accion=filtrar&nombre=${nombreProveedor}`, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            listaProveedores.innerHTML = xhr.responseText;
            listaProveedores.style.display = "block";
        }
    };
    xhr.send();
}

// Función para seleccionar un proveedor de la lista
function seleccionarProveedor(idProveedor, nombreProveedor) {
    document.getElementById("proveedorNombre").value = nombreProveedor;
    document.getElementById("idProveedor").value = idProveedor;
    document.getElementById("listaProveedores").innerHTML = "";
    document.getElementById("listaProveedores").style.display = "none";

    buscarProducto('', idProveedor);
}

// Evento para cerrar la lista de proveedores al hacer clic fuera
document.addEventListener("click", function(event) {
    const listaProveedores = document.getElementById("listaProveedores");
    if (!event.target.closest('#proveedorNombre') && !event.target.closest('#listaProveedores')) {
        listaProveedores.style.display = "none";
    }
});

/* Seleccionar producto por nombre */
// Evento para el input de productos
const inputProducto = document.getElementById("nombre"); // Campo de búsqueda de productos
inputProducto.addEventListener("input", function() {
    buscarProducto(this.value);
});

// Evento para mostrar todos los productos al hacer clic en el input
inputProducto.addEventListener("click", function() {
    const listaProductos = document.getElementById("listaProductos");
    listaProductos.style.display = "block";
    buscarProducto(''); // Cargar todos los productos
});

// Función para buscar productos
function buscarProducto(nombreProducto, idProveedor=null) {
    const listaProductos = document.getElementById("listaProductos");
    const xhr = new XMLHttpRequest();

    let url = `/SolucionesWeb/Static/Controller/Productos.php?accion=filtrar&nombre=${nombreProducto}`;
    if (idProveedor) {
        url += `&idProveedor=${idProveedor}`;
    }

    xhr.open("GET", url, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            listaProductos.innerHTML = xhr.responseText;
            listaProductos.style.display = "block";
        }
    };
    xhr.send();
}



// Función para seleccionar un producto de la lista
function seleccionarProducto(folio, nombreProducto) {
    document.getElementById("nombre").value = nombreProducto; // Mostrar el nombre
    document.getElementById("idProducto").value = folio; // Guardar el folio en un campo oculto
    document.getElementById("listaProductos").innerHTML = "";
    document.getElementById("listaProductos").style.display = "none";
}

// Evento para cerrar la lista de productos al hacer clic fuera
document.addEventListener("click", function(event) {
    const listaProductos = document.getElementById("listaProductos");
    if (!event.target.closest('#nombre') && !event.target.closest('#listaProductos')) {
        listaProductos.style.display = "none";
    }
});

