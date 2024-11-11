<?php include 'HeaderA.php'?>
<?php include '../../Controller/Sesion.php'; ?>

    <div class="main-content">
 
        <section class="reports">

        
            <header>
                <h1>Dashboard</h1>
                <p>¡Bienvenido Administrador!</p>
            </header>
            <h2>Reportes</h2>
            <div class="report-grid">
                
                <a href="#" class="report-box" id="productos-vendidos">Productos más vendidos</a>

                <a href="#" class="report-box" id="total-ventas">Total de ventas</a>

                <a href="#" class="report-box" id="existencias-productos">Existencias de productos por proveedor</a>
                
                <a href="#" class="report-box" id="clientes-compras">Clientes con más compras</a>
            </div>
            <div class="report-item">
                <a href="#" class="report-box" id="mejores-precios">Proveedores con mejores precios</a>
            </div>
        </section>

        <section class="consultas">
            <img src="/SolucionesWeb/Static/img/logosinletras.png" alt="logoempresa" class="logo-image">
            <h2>Consultas</h2>
                <a href="/SolucionesWeb/Static/Controller/ConsultaListaClientes.php" class="consultas-box" id="descargar-clientes">Descargar lista de clientes</a>

                <a href="#" class="consultas-box" id="descargar-productos">Descargar lista de productos</a>
        </section>
    </div>

    <script src="../../Controller/Js/Admin.js"></script>
</body>
</html>
