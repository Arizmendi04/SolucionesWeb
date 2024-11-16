    // Mostrar formulario de clientes
    document.getElementById('descargar-clientes').addEventListener('click', function(event) {
        document.getElementById('clientes-container').style.display = 'block';
        document.getElementById('productos-container').style.display = 'none';
        document.getElementById('productos-disponibles-container').style.display = 'none';
        document.getElementById('mejores-precios-container').style.display = 'none';
        event.stopPropagation(); // Evita que el clic se propague al documento
    });

    // Mostrar formulario de productos
    document.getElementById('descargar-productos').addEventListener('click', function(event) {
        document.getElementById('productos-container').style.display = 'block';
        document.getElementById('clientes-container').style.display = 'none';
        document.getElementById('productos-disponibles-container').style.display = 'none';
        document.getElementById('mejores-precios-container').style.display = 'none';
        event.stopPropagation(); // Evita que el clic se propague al documento
    });

    //Mostrar formulario de reporte de existencias de productos
    document.getElementById('existencias-productos').addEventListener('click', function(event) {
        document.getElementById('productos-disponibles-container').style.display = 'block';
        document.getElementById('clientes-container').style.display = 'none';
        document.getElementById('productos-container').style.display = 'none';
        document.getElementById('mejores-precios-container').style.display = 'none';
        event.stopPropagation(); // Evita que el clic se propague al documento
    });

    //Mostrar formulario de mejores precios por proveedor
    document.getElementById('mejores-precios').addEventListener('click', function(event) {
        document.getElementById('mejores-precios-container').style.display = 'block';
        document.getElementById('clientes-container').style.display = 'none';
        document.getElementById('productos-container').style.display = 'none';
        document.getElementById('productos-disponibles-container').style.display = 'none';
        event.stopPropagation(); // Evita que el clic se propague al documento
    });

    // Ocultar formularios al hacer clic fuera de ellos
    document.addEventListener('click', function(event) {
        const clientesContainer = document.getElementById('clientes-container');
        const productosContainer = document.getElementById('productos-container');
        const existenciasProductosContainer = document.getElementById('productos-disponibles-container');
        const mejoresPreciosContainer = document.getElementById('mejores-precios-container');

        // Verifica si el clic ocurrió fuera de los contenedores
        if (!clientesContainer.contains(event.target) && 
            !productosContainer.contains(event.target) && 
            !existenciasProductosContainer.contains(event.target) && 
            !mejoresPreciosContainer.contains(event.target)) {
            clientesContainer.style.display = 'none';
            productosContainer.style.display = 'none';
            existenciasProductosContainer.style.display = 'none';
            mejoresPreciosContainer.style.display = 'none';
        }
    });

