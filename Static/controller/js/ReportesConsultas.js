// Mostrar formulario de clientes
document.getElementById('descargar-clientes').addEventListener('click', function(event) {
    document.getElementById('clientes-container').style.display = 'block';
    document.getElementById('productos-container').style.display = 'none';
    document.getElementById('empleados-container').style.display = 'none';
    document.getElementById('productos-vendidos-container').style.display = 'none';
    document.getElementById('ventas-mensuales-container').style.display = 'none';
    document.getElementById('productos-disponibles-container').style.display = 'none';
    document.getElementById('clientes-mensuales-container').style.display = 'none';
    document.getElementById('mejores-precios-container').style.display = 'none';
    event.stopPropagation(); // Evita que el clic se propague al documento
});

// Mostrar formulario de productos
document.getElementById('descargar-productos').addEventListener('click', function(event) {
    document.getElementById('productos-container').style.display = 'block';
    document.getElementById('clientes-container').style.display = 'none';
    document.getElementById('empleados-container').style.display = 'none';
    document.getElementById('productos-vendidos-container').style.display = 'none';
    document.getElementById('ventas-mensuales-container').style.display = 'none';
    document.getElementById('productos-disponibles-container').style.display = 'none';
    document.getElementById('clientes-mensuales-container').style.display = 'none';
    document.getElementById('mejores-precios-container').style.display = 'none';
    event.stopPropagation(); // Evita que el clic se propague al documento
});

// Mostrar formulario de empleados
document.getElementById('descargar-empleados').addEventListener('click', function(event) {
    document.getElementById('empleados-container').style.display = 'block';
    document.getElementById('clientes-container').style.display = 'none';
    document.getElementById('productos-container').style.display = 'none';
    document.getElementById('productos-vendidos-container').style.display = 'none';
    document.getElementById('ventas-mensuales-container').style.display = 'none';
    document.getElementById('productos-disponibles-container').style.display = 'none';
    document.getElementById('clientes-mensuales-container').style.display = 'none';
    document.getElementById('mejores-precios-container').style.display = 'none';
    event.stopPropagation(); // Evita que el clic se propague al documento
});

// Mostrar formulario de reporte de productos vendidos
document.getElementById('productos-vendidos').addEventListener('click', function(event) {
    document.getElementById('productos-vendidos-container').style.display = 'block';
    document.getElementById('clientes-container').style.display = 'none';
    document.getElementById('productos-container').style.display = 'none';
    document.getElementById('empleados-container').style.display = 'none';
    document.getElementById('ventas-mensuales-container').style.display = 'none';
    document.getElementById('productos-disponibles-container').style.display = 'none';
    document.getElementById('clientes-mensuales-container').style.display = 'none';
    document.getElementById('mejores-precios-container').style.display = 'none';
    event.stopPropagation(); // Evita que el clic se propague al documento
});

//Mostrar formulario de reporte de ventas
document.getElementById('total-ventas').addEventListener('click', function(event) {
    document.getElementById('ventas-mensuales-container').style.display = 'block';
    document.getElementById('clientes-container').style.display = 'none';
    document.getElementById('productos-container').style.display = 'none';
    document.getElementById('empleados-container').style.display = 'none';
    document.getElementById('productos-vendidos-container').style.display = 'none';
    document.getElementById('productos-disponibles-container').style.display = 'none';
    document.getElementById('clientes-mensuales-container').style.display = 'none';
    document.getElementById('mejores-precios-container').style.display = 'none';
    event.stopPropagation(); // Evita que el clic se propague al documento
});

//Mostrar formulario de reporte de existencias de productos
document.getElementById('existencias-productos').addEventListener('click', function(event) {
    document.getElementById('productos-disponibles-container').style.display = 'block';
    document.getElementById('clientes-container').style.display = 'none';
    document.getElementById('productos-container').style.display = 'none';
    document.getElementById('empleados-container').style.display = 'none';
    document.getElementById('productos-vendidos-container').style.display = 'none';
    document.getElementById('ventas-mensuales-container').style.display = 'none';
    document.getElementById('clientes-mensuales-container').style.display = 'none';
    document.getElementById('mejores-precios-container').style.display = 'none';
    event.stopPropagation(); // Evita que el clic se propague al documento
});

//Mostrar formulario de compras por cliente
document.getElementById('clientes-compras').addEventListener('click', function(event) {
    document.getElementById('clientes-mensuales-container').style.display = 'block';
    document.getElementById('clientes-container').style.display = 'none';
    document.getElementById('productos-container').style.display = 'none';
    document.getElementById('empleados-container').style.display = 'none';
    document.getElementById('productos-vendidos-container').style.display = 'none';
    document.getElementById('ventas-mensuales-container').style.display = 'none';
    document.getElementById('productos-disponibles-container').style.display = 'none';
    document.getElementById('mejores-precios-container').style.display = 'none';
    event.stopPropagation(); // Evita que el clic se propague al documento
});

//Mostrar formulario de mejores precios por proveedor
document.getElementById('mejores-precios').addEventListener('click', function(event) {
    document.getElementById('mejores-precios-container').style.display = 'block';
    document.getElementById('clientes-container').style.display = 'none';
    document.getElementById('productos-container').style.display = 'none';
    document.getElementById('empleados-container').style.display = 'none';
    document.getElementById('productos-vendidos-container').style.display = 'none';
    document.getElementById('ventas-mensuales-container').style.display = 'none';
    document.getElementById('productos-disponibles-container').style.display = 'none';
    document.getElementById('clientes-mensuales-container').style.display = 'none';
    event.stopPropagation(); // Evita que el clic se propague al documento
});



// Ocultar formularios al hacer clic fuera de ellos
document.addEventListener('click', function(event) {
    const clientesContainer = document.getElementById('clientes-container');
    const productosContainer = document.getElementById('productos-container');
    const empleadosContainer = document.getElementById('empleados-container');
    const productosVendidosContainer = document.getElementById('productos-vendidos-container');
    const ventasMensualesContainer = document.getElementById('ventas-mensuales-container');
    const existenciasProductosContainer = document.getElementById('productos-disponibles-container');
    const clientesMensualesContainer = document.getElementById('clientes-mensuales-container');
    const mejoresPreciosContainer = document.getElementById('mejores-precios-container');

    // Verifica si el clic ocurrió fuera de los contenedores
    if (!clientesContainer.contains(event.target) && 
        !productosContainer.contains(event.target) && 
        !empleadosContainer.contains(event.target) && 
        !productosVendidosContainer.contains(event.target)&& 
        !ventasMensualesContainer.contains(event.target) && 
        !existenciasProductosContainer.contains(event.target) && 
        !clientesMensualesContainer.contains(event.target)&& 
        !mejoresPreciosContainer.contains(event.target)) {
        clientesContainer.style.display = 'none';
        productosContainer.style.display = 'none';
        empleadosContainer.style.display = 'none';
        productosVendidosContainer.style.display = 'none';
        ventasMensualesContainer.style.display = 'none';
        existenciasProductosContainer.style.display = 'none';
        clientesMensualesContainer.style.display = 'none';
        mejoresPreciosContainer.style.display = 'none';
    }
});

