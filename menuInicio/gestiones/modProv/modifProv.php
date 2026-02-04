<html>
    <head>
        <title>Modificar Proveedor</title>
        <meta charset="utf-8">
        <?php
        include '../../../BD/conex.php'
        ?>
        <style>
        /* ========================================
        RESET BÁSICO
        ======================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* ========================================
        ESTILOS DEL BODY
        ======================================== */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* ========================================
        TÍTULO PRINCIPAL
        ======================================== */
        h1 {
            color: white;
            font-size: 38px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            letter-spacing: 0.5px;
        }

        h3 {
            color: white;
            font-size: 24px;
            font-weight: 600;
            text-align: center;
            margin: 30px 0 20px 0;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        /* ========================================
        COMENTARIOS/MENSAJES
        ======================================== */
        #comentario {
            color: white;
            font-size: 16px;
            text-align: center;
            margin: 0 auto 30px auto;
            min-height: 24px;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
            max-width: 600px;
        }

        #comentario[style*="color: red"],
        #comentario[style*="color:red"] {
            background: rgba(245, 101, 101, 0.2);
            border: 2px solid rgba(245, 101, 101, 0.5);
            color: #fff5f5 !important;
            box-shadow: 0 4px 15px rgba(245, 101, 101, 0.3);
            animation: slideInDown 0.5s ease-out;
        }

        p[style*="color: orange"],
        p[style*="color:orange"] {
            color: #fbd38d !important;
            background: rgba(237, 137, 54, 0.2);
            border: 2px solid rgba(237, 137, 54, 0.5);
            padding: 12px 20px;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            max-width: 500px;
            margin: 20px auto;
        }

        /* ========================================
        FORMULARIO DE BÚSQUEDA
        ======================================== */
        form {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 15px;
            padding: 20px 25px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            max-width: 500px;
            width: 100%;
            display: flex;
            gap: 10px;
        }

        input[type="text"] {
            flex: 1;
            padding: 12px 18px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 15px;
            background: #f7fafc;
            transition: all 0.3s ease;
            color: #2d3748;
            font-family: inherit;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        input[type="text"]:hover {
            border-color: #cbd5e0;
            background: white;
        }

        input[type="text"]::placeholder {
            color: #a0aec0;
            font-style: italic;
        }

        input[type="submit"] {
            padding: 12px 25px;
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        input[type="submit"]:hover {
            background: linear-gradient(135deg, #3182ce 0%, #2c5282 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(66, 153, 225, 0.4);
        }

        /* ========================================
        TARJETA DE PROVEEDOR
        ======================================== */
        .proveedor-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 15px;
            padding: 25px 30px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            width: 100%;
            max-width: 500px;
            transition: all 0.3s ease;
        }

        .proveedor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .proveedor-info {
            color: #2d3748;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .proveedor-info:last-of-type {
            margin-bottom: 15px;
        }

        .proveedor-info strong {
            color: #1a202c;
            font-weight: 600;
            display: inline-block;
            min-width: 180px;
        }

        /* ========================================
        BOTONES
        ======================================== */
        .btn-editar {
            display: inline-block !important;
            width: auto !important;
            padding: 10px 25px !important;
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%) !important;
            color: white !important;
            text-decoration: none !important;
            border-radius: 8px !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.8px !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 12px rgba(66, 153, 225, 0.3) !important;
            position: relative !important;
            overflow: hidden !important;
            margin-right: 10px !important;
            border: none !important;
        }

        .btn-editar:hover {
            background: linear-gradient(135deg, #3182ce 0%, #2c5282 100%) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 18px rgba(66, 153, 225, 0.5) !important;
        }

        .btn-eliminar {
            display: inline-block !important;
            width: auto !important;
            padding: 10px 25px !important;
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%) !important;
            color: white !important;
            text-decoration: none !important;
            border-radius: 8px !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.8px !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 12px rgba(245, 101, 101, 0.3) !important;
            position: relative !important;
            overflow: hidden !important;
            border: none !important;
        }

        .btn-eliminar:hover {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 18px rgba(245, 101, 101, 0.5) !important;
        }

        /* ========================================
        ENLACE VOLVER
        ======================================== */
        a[href*='menu.php'] {
            display: inline-block;
            margin-top: 40px;
            padding: 12px 25px;
            color: white;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid white;
            border-radius: 8px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        a[href*='menu.php']:hover {
            background: white;
            color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3);
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* RESPONSIVE */
        @media (max-width: 480px) {
            .btn-editar,
            .btn-eliminar {
                display: block !important;
                width: 100% !important;
                margin-right: 0 !important;
                margin-bottom: 8px !important;
            }
}
    </style>
    </head>
    <body>
        <h1>Seleccione un proveedor para modificar</h1>
        <a href="../../menu.php">Volver al Menú</a>
        <p id="comentario"></p>
        <form action="" method="get">
            <input type="text" name="buscar" placeholder="Buscar por nombre..." value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>">
            <input type="submit" value="Buscar">
        </form>
        <?php
        if(isset($_GET['buscar']) && !empty($_GET['buscar'])) {
            $busqueda = $_GET['buscar'];
            $resultado = mysqli_query($link, "SELECT * FROM proveedor WHERE nombre LIKE '%$busqueda%' AND estado = 1 ORDER BY nombre asc");
            
            if(mysqli_num_rows($resultado) > 0) {
                echo "<h3>Resultados de búsqueda:</h3>";
                while($proveedor = mysqli_fetch_assoc($resultado)) {
                    echo '<div class="proveedor-card">';
                    echo '<p class="proveedor-info"><strong>Número de proveedor:</strong> ' . $proveedor['id'] . '</p>'; 
                    echo '<p class="proveedor-info"><strong>Nombre del proveedor:</strong> ' . $proveedor['nombre'] . '</p>'; 
                    echo '<p class="proveedor-info"><strong>Teléfono:</strong> ' . $proveedor['telefono'] . '</p>';
                    echo "<a href='parteDos.php?id=" . $proveedor['id'] . "' class='btn-editar'>Editar</a>";
                    echo "<a href='borrarProv.php?id=" . $proveedor['id'] . "' class='btn-eliminar'>Eliminar</a>";
                    echo '</div>';
                }
            } else {
                echo "<p style='color: orange;'>No se encontraron proveedores con ese nombre</p>";
            }
        }else {
            $result = mysqli_query($link,"SELECT * FROM proveedor WHERE estado = 1 ORDER BY nombre asc");
            if(mysqli_num_rows($result) > 0) {
                while($proveedor = mysqli_fetch_array($result)) {
                    echo '<div class="proveedor-card">';
                    echo '<p class="proveedor-info"><strong>Número de proveedor:</strong> ' . $proveedor['id'] . '</p>'; 
                    echo '<p class="proveedor-info"><strong>Nombre del proveedor:</strong> ' . $proveedor['nombre'] . '</p>';
                    echo '<p class="proveedor-info"><strong>Teléfono:</strong> ' . $proveedor['telefono'] . '</p>'; 
                    echo "<a href='parteDos.php?id=" . $proveedor['id'] . "' class='btn-editar'>Editar</a>";
                    echo "<a href='borrarProv.php?id=" . $proveedor['id'] . "' class='btn-eliminar'>Eliminar</a>";
                    echo '</div>';
                }
            } else {
                echo '<script>
                            document.getElementById("comentario").style.color = "red";
                            document.getElementById("comentario").innerHTML = "No se encontraron proveedores";
                    </script>';
            }
        }
        ?>
        
    </body>
</html>