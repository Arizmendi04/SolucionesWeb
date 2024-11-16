<?php include 'HeaderE.php'?>
<?php include '../../Controller/Sesion.php'; ?>
<?php include '../../Controller/Connect/Db.php'; ?>
<?php include '../../Controller/ControllerEmpleado/Proveedores.php'; ?>

    <link rel="stylesheet" href="../../Css/filtroForm.css">
    <div class="main-content">

        <section class="reports">
            <header>
                <h1>Dashboard</h1>
                <p>¡Bienvenido Empleado!</p>
            </header>
            <h2>Reportes</h2>
            <div class="report-grid">

                <a href="#" class="report-box" id="existencias-productos">Existencias de productos por proveedor</a>

                <a href="#" class="report-box" id="mejores-precios">Proveedores con mejores precios</a>

            </div>

        </section>

        <!-- Formulario para el reporte de productos disponibles (existencia) por tipo y proveedor-->
        <div id="productos-disponibles-container" class="container" style="display:none;">
            <h3>Generar Reporte de Productos Disponibles por tipo y proveedor</h3>
            <form action="/SolucionesWeb/Static/Controller/ReporteExistenciasProd.php" id="descargarProdExis" class="formDescargaProdExis" method="GET">
                <!-- Combobox para seleccionar la categoría-->
                <label for="categoria">Categoría:</label>
                <select id="categoria" name="categoria" required>
                    <option value="Insecticida">Insecticida</option>
                    <option value="Herbicida">Herbicida</option>
                    <option value="Fertilizante">Fertilizante</option>
                    <option value="Hormiguicida">Hormiguicida</option>
                    <option value="Cucarachicida">Cucarachicida</option>
                    <option value="Trampa">Trampa</option>
                    <option value="Mosquicida">Mosquicida</option>
                    <option value="Otro">Otro</option>
                </select>

                <!-- Combobox para seleccionar el proveedor -->
                <label for="proveedor">Seleccione el Proveedor:</label>
                <select id="proveedor" name="proveedor" required>
                    <?php
                        // Código PHP para obtener la lista de proveedores
                        $proveedores = solicitarProveedores($conn);
                        foreach ($proveedores as $proveedor) {
                            echo "<option value='{$proveedor['idProveedor']}'>{$proveedor['razonSocial']}</option>";
                        }
                    ?>
                </select>
                <button type="submit">Generar PDF</button>
            </form>
        </div>
        
        <!-- Formulario para el reporte de proveedores con mejores precios-->
        <div id="mejores-precios-container" class="container" style="display:none;">
            <h3>Generar Reporte de Proveedores con Mejores Precios</h3>
            <form action="/SolucionesWeb/Static/Controller/ReporteMejoresPrecios.php" id="descargarProdExis" class="formDescargaProdExis" method="GET">
                <!-- Combobox para seleccionar la categoría-->
                <label for="categoria">Categoría:</label>
                <select id="categoria" name="categoria" required>
                    <option value="Insecticida">Insecticida</option>
                    <option value="Herbicida">Herbicida</option>
                    <option value="Fertilizante">Fertilizante</option>
                    <option value="Hormiguicida">Hormiguicida</option>
                    <option value="Cucarachicida">Cucarachicida</option>
                    <option value="Trampa">Trampa</option>
                    <option value="Mosquicida">Mosquicida</option>
                    <option value="Otro">Otro</option>
                </select>
                
                <label for="peso">Peso:</label>
                <input type="number" id="peso" name="peso" min="0.1" step="0.1" required>

                <!-- Filtro por Unidad de Medida -->
                <label for="unidad">Unidad de Medida:</label>
                <select id="unidad" name="unidad" required>
                    <option value="kilogramos">Kilogramos</option>
                    <option value="litros">Litros</option>
                    <option value="gramos">Gramos</option>
                    <option value="mililitros">Mililitros</option>
                </select>

                <button type="submit">Generar Excel</button>
            </form>
        </div>

        <section class="consultas">
            <img src="/SolucionesWeb/Static/img/logosinletras.png" alt="logoempresa" class="logo-image">
            <h2>Consultas</h2>
                <a href="#" class="consultas-box" id="descargar-clientes">Descargar lista de clientes</a>
                <a href="#" class="consultas-box" id="descargar-productos">Descargar lista de productos</a>
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

    </div>
    <script src="../../Controller/Js/ReportesConsultas.js"></script>
</body>
</html>
