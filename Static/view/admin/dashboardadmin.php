<?php include 'HeaderA.php'?>
<?php include '../../Controller/Sesion.php'; ?>

    <link rel="stylesheet" href="../../Css/filtroForm.css">
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
                <a href="#" class="consultas-box" id="descargar-clientes">Descargar lista de clientes</a>
                <a href="#" class="consultas-box" id="descargar-productos">Descargar lista de productos</a>
                <a href="#" class="consultas-box" id="descargar-empleados">Descargar lista de empleados</a>
        </section>

        <!-- Formulario de filtro (inicialmente oculto) -->
        <div id="clientes-container" class="container" style="display:none;">
            <h3>Descargar Lista de Clientes</h3>
            <form action="/SolucionesWeb/Static/Controller/ConsultaListaClientes.php" id="descargarClie" class="formDescargaClie" method="GET">
                <label for="estado">Seleccione un estado:</label>
                <select id="estado" name="estado">
                    <option value="">Todos</option>
                    <option value="Aguascalientes">Aguascalientes</option>
                    <option value="Baja California">Baja California</option>
                    <option value="Baja California Sur">Baja California Sur</option>
                    <option value="Campeche">Campeche</option>
                    <option value="Chiapas">Chiapas</option>
                    <option value="Chihuahua">Chihuahua</option>
                    <option value="Coahuila">Coahuila</option>
                    <option value="Colima">Colima</option>
                    <option value="Durango">Durango</option>
                    <option value="Guanajuato">Guanajuato</option>
                    <option value="Guerrero">Guerrero</option>
                    <option value="Hidalgo">Hidalgo</option>
                    <option value="Jalisco">Jalisco</option>
                    <option value="Ciudad de México">Ciudad de México</option>
                    <option value="Michoacán">Michoacán</option>
                    <option value="Morelos">Morelos</option>
                    <option value="Nayarit">Nayarit</option>
                    <option value="Nuevo León">Nuevo León</option>
                    <option value="Oaxaca">Oaxaca</option>
                    <option value="Puebla">Puebla</option>
                    <option value="Querétaro">Querétaro</option>
                    <option value="Quintana Roo">Quintana Roo</option>
                    <option value="San Luis Potosí">San Luis Potosí</option>
                    <option value="Sinaloa">Sinaloa</option>
                    <option value="Sonora">Sonora</option>
                    <option value="Tabasco">Tabasco</option>
                    <option value="Tamaulipas">Tamaulipas</option>
                    <option value="Tlaxcala">Tlaxcala</option>
                    <option value="Veracruz">Veracruz</option>
                    <option value="Yucatán">Yucatán</option>
                    <option value="Zacatecas">Zacatecas</option>
                </select>
                <button type="submit">Descargar Excel</button>
            </form>
        </div>

        <div id="productos-container" class="container" style="display:none;">
            <h3>Descargar Lista de Productos</h3>
            <form action="/SolucionesWeb/Static/Controller/ConsultaListaProd.php" id="descargarProd" class="formDescargaProd" method="GET">
                <label for="categoria">Seleccione la categoría del producto:</label>
                <select id="categoria" name="categoria">
                    <option value="">Todos</option>
                    <option value="Insecticida">Insecticida</option>
                    <option value="Herbicida">Herbicida</option>
                    <option value="Fertilizante">Fertilizante</option>
                    <option value="Hormiguicida">Hormiguicida</option>
                    <option value="Cucarachicida">Cucarachicida</option>
                    <option value="Trampa">Trampa</option>
                    <option value="Mosquicida">Mosquicida</option>
                </select>
                <button type="submit">Descargar Excel</button>
            </form>
        </div>

        <div id="empleados-container" class="container" style="display:none;">
            <h3>Descargar Lista de Empleados</h3>
            <form action="/SolucionesWeb/Static/Controller/ConsultaListaEmp.php" id="descargarEmp" class="formDescargaEmp" method="GET">
                <label for="categoria">Seleccione el género del empleado:</label>
                <select id="categoria" name="categoria">
                    <option value="">Todos</option>
                    <option value="Femenino">Femenino</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Otro">Otro</option>
                </select>
                <button type="submit">Descargar Excel</button>
            </form>
        </div>


    </div>

    <script>
        // Mostrar formulario de clientes
        document.getElementById('descargar-clientes').addEventListener('click', function(event) {
            document.getElementById('clientes-container').style.display = 'block';
            document.getElementById('productos-container').style.display = 'none';
            document.getElementById('empleados-container').style.display = 'none';
            event.stopPropagation(); // Evita que el clic se propague al documento
        });

        // Mostrar formulario de productos
        document.getElementById('descargar-productos').addEventListener('click', function(event) {
            document.getElementById('productos-container').style.display = 'block';
            document.getElementById('clientes-container').style.display = 'none';
            document.getElementById('empleados-container').style.display = 'none';
            event.stopPropagation(); // Evita que el clic se propague al documento
        });

        // Mostrar formulario de empleados
        document.getElementById('descargar-empleados').addEventListener('click', function(event) {
            document.getElementById('empleados-container').style.display = 'block';
            document.getElementById('clientes-container').style.display = 'none';
            document.getElementById('productos-container').style.display = 'none';
            event.stopPropagation(); // Evita que el clic se propague al documento
        });

        // Ocultar formularios al hacer clic fuera de ellos
        document.addEventListener('click', function(event) {
            const clientesContainer = document.getElementById('clientes-container');
            const productosContainer = document.getElementById('productos-container');
            const empleadosContainer = document.getElementById('empleados-container');

            // Verifica si el clic ocurrió fuera de los contenedores
            if (!clientesContainer.contains(event.target) && 
                !productosContainer.contains(event.target) && 
                !empleadosContainer.contains(event.target)) {
                clientesContainer.style.display = 'none';
                productosContainer.style.display = 'none';
                empleadosContainer.style.display = 'none';
            }
        });
    </script>



</body>
</html>
