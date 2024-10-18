document.getElementById('descargar-clientes').addEventListener('click', function(e) {
    e.preventDefault();
    if (confirm('¿Seguro que deseas descargar la lista de clientes?')) {
        window.location.href = 'descargar_clientes.php';
    }
});

document.getElementById('descargar-productos').addEventListener('click', function(e) {
    e.preventDefault();
    if (confirm('¿Seguro que deseas descargar la lista de productos?')) {
        window.location.href = 'descargar_productos.php';
    }
});