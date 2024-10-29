<?php include 'HeaderA.php'?>
<?php include '../../Controller/Sesion.php'; ?>

<div class="main-content">
        <header>
            <h1>Dashboard</h1>
            <p>Bienvenido Admin!</p>
        </header>
        <section class="reports">
            <h2>Reportes</h2>
            <div class="report-grid">
                
                <a href="#" class="report-box" id="productos-vendidos">Productos más vendidos</a>

                <a href="#" class="report-box" id="total-ventas">Total de ventas</a>

                <a href="#" class="report-box" id="existencias-productos">Existencias de productos por proveedor</a>
                
                <a href="#" class="report-box" id="clientes-compras">Clientes con más compras</a>

                <a href="#" class="report-box" id="mejores-precios">Proveedores con mejores precios</a>

                <a href="#" class="report-box" id="descargar-clientes">Descargar lista de clientes</a>

                <a href="#" class="report-box" id="descargar-productos">Descargar lista de productos</a>
            </div>
        </section>
    </div>

    <script src="../../Controller/Js/Admin.js"></script>
</body>
</html>
