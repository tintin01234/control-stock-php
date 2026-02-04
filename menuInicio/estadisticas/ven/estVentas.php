<html>
    <head>
        <meta charset="utf-8">
        <title>Estadísticas de Ventas</title>
        <?php
        include '../../../BD/conex.php';
        $fechaHoy = date('Y-m-d');
        ?>
        <link rel="stylesheet" href="estiloestVentas.css">
    </head>
    <body>
        <div class="container">
            <h1>Estadísticas de Ventas</h1>
            <p id="comentario"></p>
            <a href="../menuEst.php">Volver</a>
            
            <form action="" method="post">
                <h3>Seleccione el período a analizar</h3>
                <label>Desde:</label>
                <input type="date" name="fechaD" id="fechaD" required>
                
                <label>Hasta:</label>
                <input type="date" name="fechaH" id="fechaH" required max="<?php echo $fechaHoy; ?>">
                
                <input type="submit" name="btnAceptar" value="Ver Estadísticas">
            </form>
            
            <?php
            $nombresJSON = '[]';
            $cantidadesJSON = '[]';
            $totalUnidades = 0;
            $hayDatos = false;
            $nombres = array();
            $cantidades = array();
            
            if(!empty($_POST['fechaD']) && !empty($_POST['fechaH']) && $_SERVER['REQUEST_METHOD'] == "POST") {
                $fechaDesde = $_POST['fechaD'];
                $fechaHasta = $_POST['fechaH'];
                
                if($fechaDesde <= $fechaHasta) {
                    
                    $ordenarProd = mysqli_query($link,
                        "SELECT producto.nombre, SUM(producto_venta.cantidad) as total_cantidad 
                         FROM producto 
                         JOIN producto_venta ON producto.id = producto_venta.idProd 
                         JOIN venta ON venta.id = producto_venta.idVenta 
                         WHERE fecha_venta BETWEEN '$fechaDesde' AND '$fechaHasta'
                         GROUP BY producto.id, producto.nombre 
                         ORDER BY total_cantidad DESC");
                    
                    $resultTotal = mysqli_query($link,
                        "SELECT SUM(producto_venta.cantidad) as total
                         FROM producto_venta
                         JOIN venta ON producto_venta.idVenta = venta.id 
                         WHERE fecha_venta BETWEEN '$fechaDesde' AND '$fechaHasta'");
                    
                    $totalRow = mysqli_fetch_assoc($resultTotal);
                    $totalUnidades = $totalRow['total'] ? $totalRow['total'] : 0;
                    
                    if(mysqli_num_rows($ordenarProd) > 0) {
                        $hayDatos = true;
                        
                        // ⭐ Guardar datos en arrays para el gráfico y la lista
                        while($producto = mysqli_fetch_assoc($ordenarProd)) {
                            $nombres[] = $producto['nombre'];
                            $cantidades[] = intval($producto['total_cantidad']);
                        }
                        
                        $nombresJSON = json_encode($nombres);
                        $cantidadesJSON = json_encode($cantidades);
                        
                    } else {
                        echo '<p style="color:orange;"> No se encontraron ventas en ese período.</p>';
                    }
                    
                } else {
                    echo '<script>
                        document.getElementById("comentario").style.color = "red";
                        document.getElementById("comentario").innerHTML = " La fecha inicial debe ser anterior o igual a la fecha final";
                    </script>';
                }
            }
            ?>
            
            <!-- ⭐ PRIMERO: Mostrar gráfico si hay datos -->
            <?php if($hayDatos): ?>
                <div class="resultados-container">
                    <h3>Estadísticas del <?php echo date('d/m/Y', strtotime($fechaDesde)); ?> 
                        al <?php echo date('d/m/Y', strtotime($fechaHasta)); ?></h3>
                    
                    <div style='background:#e3f2fd; padding:15px; margin:10px 0; border-radius:8px;'>
                        <h4>Total de unidades vendidas: <strong><?php echo $totalUnidades; ?></strong></h4>
                    </div>
                    
                    <!-- ⭐ GRÁFICO PRIMERO -->
                    <div class="grafico-container">
                        <h3>Gráfico de Productos Vendidos</h3>
                        <div id="chart_div" style="width: 100%; height: 500px;"></div>
                    </div>
                    
                    <!-- ⭐ LUEGO: Lista detallada -->
                    <div style="margin-top: 40px;">
                        <h4>Detalle por producto:</h4>
                        <?php foreach($nombres as $i => $nombre): ?>
                            <div style="background:#f5f5f5; padding:10px; margin:5px 0; border-radius:5px;">
                                <strong>Producto:</strong> <?php echo htmlspecialchars($nombre); ?><br>
                                <strong>Cantidad:</strong> <?php echo $cantidades[$i]; ?> unidades
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                <script type="text/javascript">
                    google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {
                        var data = google.visualization.arrayToDataTable([
                            ['Producto', 'Cantidad', { role: 'style' }],
                            <?php 
                            foreach($nombres as $i => $nombre) {
                                echo "['" . addslashes($nombre) . "', " . $cantidades[$i] . ", 'color: #667eea'],\n";
                            }
                            ?>
                        ]);

                        var options = {
                            title: 'Productos Más Vendidos (Total: <?php echo $totalUnidades; ?> unidades)',
                            titleTextStyle: {
                                fontSize: 18,
                                bold: true,
                                color: '#2c3e50'
                            },
                            chartArea: {
                                width: '75%',
                                height: '70%'
                            },
                            hAxis: {
                                title: 'Productos',
                                titleTextStyle: {
                                    fontSize: 14,
                                    italic: false,
                                    color: '#2c3e50'
                                },
                                textStyle: {
                                    fontSize: 12
                                }
                            },
                            vAxis: {
                                title: 'Cantidad Vendida',
                                titleTextStyle: {
                                    fontSize: 14,
                                    italic: false,
                                    color: '#2c3e50'
                                },
                                minValue: 0,
                                format: '0',
                                gridlines: {
                                    color: '#f0f0f0'
                                },
                                textStyle: {
                                    fontSize: 12
                                }
                            },
                            legend: { 
                                position: 'none'
                            },
                            backgroundColor: '#ffffff',
                            bar: { 
                                groupWidth: '70%' 
                            },
                            animation: {
                                startup: true,
                                duration: 1000,
                                easing: 'out'
                            },
                            tooltip: {
                                textStyle: {
                                    fontSize: 13
                                }
                            }
                        };

                        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
                        chart.draw(data, options);
                        
                        // Hacer el gráfico responsive
                        window.addEventListener('resize', function() {
                            chart.draw(data, options);
                        });
                    }
                </script>
            <?php endif; ?>
        </div>
    </body>
</html>