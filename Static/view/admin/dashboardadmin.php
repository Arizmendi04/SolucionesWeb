<?php include 'HeaderA.php'?>
<?php include '../../Controller/Sesion.php'; ?>
<?php include '../../Controller/Connect/Db.php'; ?>

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

        <!-- Formulario para generar reporte de productos más vendidos -->
        <div id="productos-vendidos-container" class="container" style="display:none;">
            <h3>Generar Reporte de Productos Más Vendidos</h3>
            <form action="/SolucionesWeb/Static/Controller/ReporteProductosVendidos.php" method="GET">
                <label for="fechaDesde">Fecha desde:</label>
                <input type="date" id="fechaInicio" name="fechaInicio" required class="form-control custom-date">
                
                <label for="fechaHasta">Fecha hasta:</label>
                <input type="date" id="fechaFin" name="fechaFin" required class="form-control custom-date">
                
                <button type="submit">Generar PDF</button>
            </form>
        </div>

        <!-- Formulario para el reporte de ventas mensuales por empleado -->
        <div id="ventas-mensuales-container" class="container" style="display:none;">
            <h3>Generar Reporte de Ventas Mensuales por Empleado</h3>
            <form action="/SolucionesWeb/Static/Controller/ReporteVentasMensuales.php" id="descargarVen" class="formDescargaVen" method="GET">
                <!-- Combobox para seleccionar el mes -->
                <label for="mes">Seleccione el Mes:</label>
                <select id="mes" name="mes" required>
                    <option value="01">Enero</option>
                    <option value="02">Febrero</option>
                    <option value="03">Marzo</option>
                    <option value="04">Abril</option>
                    <option value="05">Mayo</option>
                    <option value="06">Junio</option>
                    <option value="07">Julio</option>
                    <option value="08">Agosto</option>
                    <option value="09">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select>

                <!-- Combobox para seleccionar el empleado -->
                <label for="empleado">Seleccione el Empleado:</label>
                <select id="empleado" name="empleado" required>
                    <?php
                        // Código PHP para obtener la lista de empleados
                        include '../../Controller/Empleados.php';
                        $empleados = solicitarEmpleados($conn);
                        foreach ($empleados as $empleado) {
                            echo "<option value='{$empleado['noEmpleado']}'>{$empleado['nombre']} {$empleado['apellido']}</option>";
                        }
                    ?>
                </select>
                <button type="submit">Generar Excel</button>
            </form>
        </div>

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
                        include '../../Controller/Proveedores.php';
                        $proveedores = solicitarProveedores($conn);
                        foreach ($proveedores as $proveedor) {
                            echo "<option value='{$proveedor['idProveedor']}'>{$proveedor['razonSocial']}</option>";
                        }
                    ?>
                </select>
                <button type="submit">Generar Excel</button>
            </form>
        </div>

        <!-- Formulario para el reporte de compras por cliente -->
        <div id="clientes-mensuales-container" class="container" style="display:none;">
            <h3>Generar Reporte de Compras por Cliente</h3>
            <form action="/SolucionesWeb/Static/Controller/ReporteClientesMensuales.php" id="comprasClie" class="comprasClie" method="GET">
                <!-- Combobox para seleccionar el mes -->
                <label for="mes">Seleccione el Mes:</label>
                <select id="mes" name="mes" required>
                    <option value="01">Enero</option>
                    <option value="02">Febrero</option>
                    <option value="03">Marzo</option>
                    <option value="04">Abril</option>
                    <option value="05">Mayo</option>
                    <option value="06">Junio</option>
                    <option value="07">Julio</option>
                    <option value="08">Agosto</option>
                    <option value="09">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select>
                
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
                <button type="submit">Generar Excel</button>
            </form>
        </div>

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
    <script src="../../Controller/Js/ReportesConsultas.js"></script>
</body>
</html>
