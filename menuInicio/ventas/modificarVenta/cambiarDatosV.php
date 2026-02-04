<?php
// ⭐ TODO EL PHP PRIMERO, ANTES DE CUALQUIER HTML
include '../../../BD/conex.php';
session_start();

// Obtener ID de la venta
if(!isset($_GET['id'])) {
    die("No se proporcionó un ID de venta");
}

$idVenta = intval($_GET['id']);

// ELIMINAR PRODUCTO (antes de cualquier HTML)
if(isset($_GET['eliminar_prod'])) {
    $idProductoEliminar = intval($_GET['eliminar_prod']);
    
    $deleteQuery = mysqli_query($link,"DELETE FROM producto_venta WHERE id = $idProductoEliminar");
    
    if($deleteQuery) {
        $_SESSION['mensaje_exito'] = "Producto eliminado y stock devuelto.";
    } else {
        $_SESSION['mensaje_error'] = "Error al eliminar: " . mysqli_error($link);
    }
    
    header("Location: ?id=$idVenta");
    exit;
}

//PROCESAR AGREGAR PRODUCTO 
if(isset($_POST['btnAgregarProd']) && !empty($_POST['nuevo_producto']) && !empty($_POST['nueva_cantidad'])) {
    $idProductoNuevo = intval($_POST['nuevo_producto']);
    $cantidadNueva = intval($_POST['nueva_cantidad']);

    $resultStock = mysqli_query($link,"SELECT precio, nombre FROM producto WHERE id = $idProductoNuevo AND estado = 1");
    $producto = mysqli_fetch_assoc($resultStock);
    
    if(!$producto) {
        $_SESSION['mensaje_error'] = "Producto no encontrado.";
    } else {
        $precio = $producto['precio'];
        $totalNuevo = $cantidadNueva * $precio;
        
        $verificar = mysqli_query($link,"SELECT * FROM producto_venta WHERE idVenta = $idVenta AND idProd = $idProductoNuevo");
        
        if(mysqli_num_rows($verificar) > 0) {
            $_SESSION['mensaje_error'] = "Este producto ya está en la venta.";
        } else {
            $insertQuery = mysqli_query($link,"INSERT INTO producto_venta (idProd, idVenta, cantidad, total) VALUES ($idProductoNuevo, $idVenta, $cantidadNueva, $totalNuevo)");
            
            if($insertQuery) {
                $_SESSION['mensaje_exito'] = "Producto agregado exitosamente!";
            } else {
                $_SESSION['mensaje_error'] = "Error: " . mysqli_error($link);
            }
        }
    }
    
    header("Location: ?id=$idVenta");
    exit;
}

// PROCESAR ACTUALIZACIÓN 
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btnEnv'])) {
    $idVenta = intval($_POST['idVenta']);
    
    if(isset($_POST['cantidad']) && is_array($_POST['cantidad'])) {
        foreach($_POST['cantidad'] as $idProductoVenta => $nuevaCantidad) {
            $idProductoVenta = intval($idProductoVenta);
            $nuevaCantidad = intval($nuevaCantidad);
            
            if ($nuevaCantidad <= 0) continue;
            
            // Obtener datos actuales del producto en la venta
            $resultDetalle = mysqli_query($link,"SELECT pv.idProd, pv.cantidad AS cantidad_actual, p.precio, p.cantidad AS stock_producto 
                FROM producto_venta pv 
                JOIN producto p ON p.id = pv.idProd 
                WHERE pv.id = $idProductoVenta");
            $detalle = mysqli_fetch_assoc($resultDetalle);
            
            if($detalle) {
                $cantidadActual = $detalle['cantidad_actual'];
                $stockDisponible = $detalle['stock_producto'];
                $diferencia = $nuevaCantidad - $cantidadActual;
                
                // ⭐ VALIDACIÓN: Si aumenta la cantidad, verificar que hay stock
                if ($diferencia > 0 && $diferencia > $stockDisponible) {
                    $_SESSION['mensaje_error'] = "Error: No hay suficiente stock para el producto.";
                    header("Location: ?id=$idVenta");
                    exit;
                }
                
                $precio = $detalle['precio'];
                $nuevoTotal = $nuevaCantidad * $precio;
                
                $updateQuery = mysqli_query($link,"UPDATE producto_venta SET cantidad = $nuevaCantidad, total = $nuevoTotal WHERE id = $idProductoVenta");
                
                if(!$updateQuery) {
                    $_SESSION['mensaje_error'] = "Error: " . mysqli_error($link);
                    header("Location: ?id=$idVenta");
                    exit;
                }
            }
        }
    }
    
    $nuevoEstado = isset($_POST['entregado']) ? 5 : 3;
    mysqli_query($link, "UPDATE venta SET estado = $nuevoEstado WHERE id = $idVenta");
    
    $_SESSION['mensaje_exito'] = "Venta actualizada exitosamente!";
    header("Location: modificarVenta.php");
    exit;
}

//  OBTENER DATOS PARA MOSTRAR 
$resultVenta = mysqli_query($link, "SELECT * FROM venta WHERE id = $idVenta");
$venta = mysqli_fetch_assoc($resultVenta);

if(!$venta) {
    die("No se encontró la venta");
}

$resultProductos = mysqli_query($link,"SELECT pv.id, pv.idProd, p.nombre, pv.cantidad, pv.total, p.cantidad AS stock_disponible FROM producto_venta pv JOIN producto p ON p.id = pv.idProd WHERE pv.idVenta = $idVenta");
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Modificar Venta Seleccionada</title>
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
            }

            /* ========================================
            TÍTULO PRINCIPAL
            ======================================== */
            h1 {
                color: white;
                font-size: 38px;
                font-weight: 700;
                text-align: center;
                margin-bottom: 20px;
                text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
                letter-spacing: 0.5px;
            }

            /* ========================================
            SUBTÍTULOS
            ======================================== */
            h3 {
                color: #2c3e50;
                font-size: 22px;
                font-weight: 600;
                margin-bottom: 20px;
                padding-bottom: 10px;
                border-bottom: 3px solid #667eea;
            }

            /* ========================================
            MENSAJES Y COMENTARIOS
            ======================================== */
            #comentario {
                color: white;
                font-size: 16px;
                text-align: center;
                margin: 0 auto 20px auto;
                min-height: 24px;
                font-weight: 600;
                padding: 12px 20px;
                border-radius: 10px;
                transition: all 0.3s ease;
                max-width: 800px;
                animation: slideDown 0.5s ease;
            }

            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            #comentario[style*="color: red"] {
                background: rgba(245, 101, 101, 0.2);
                border: 2px solid rgba(245, 101, 101, 0.6);
                box-shadow: 0 4px 15px rgba(245, 101, 101, 0.3);
            }

            #comentario[style*="color: green"] {
                background: rgba(72, 187, 120, 0.2);
                border: 2px solid rgba(72, 187, 120, 0.6);
                box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
            }

            /* ========================================
            INFORMACIÓN DE LA VENTA (parte superior)
            ======================================== */
            body > p {
                color: white;
                font-size: 16px;
                text-align: center;
                margin: 10px auto;
                background: rgba(255, 255, 255, 0.15);
                backdrop-filter: blur(10px);
                padding: 12px 20px;
                border-radius: 10px;
                max-width: 800px;
                font-weight: 500;
            }

            /* ========================================
            SEPARADOR HR
            ======================================== */
            hr {
                border: none;
                height: 2px;
                background: linear-gradient(to right, transparent, rgba(255,255,255,0.5), transparent);
                margin: 30px auto;
                max-width: 800px;
            }

            /* ========================================
            FORMULARIOS
            ======================================== */
            form {
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(15px);
                border-radius: 15px;
                padding: 30px;
                margin: 25px auto;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
                border: 1px solid rgba(255, 255, 255, 0.3);
                max-width: 800px;
                transition: all 0.3s ease;
            }

            form:hover {
                transform: translateY(-3px);
                box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
            }

            /* ========================================
            ITEMS DE PRODUCTO
            ======================================== */
            .producto-item {
                background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
                border-left: 4px solid #667eea;
                padding: 20px;
                margin-bottom: 20px;
                border-radius: 10px;
                transition: all 0.3s ease;
                position: relative;
            }

            .producto-item:hover {
                background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
                border-left-width: 6px;
                transform: translateX(5px);
            }

            .producto-item strong {
                color: #2c3e50;
                font-size: 18px;
                display: block;
                margin-bottom: 10px;
            }

            .producto-item small {
                color: #718096;
                font-size: 13px;
                display: block;
                margin: 5px 0;
                font-style: italic;
            }

            /* ========================================
            INPUTS
            ======================================== */
            input[type="number"] {
                width: 100%;
                max-width: 200px;
                padding: 12px 15px;
                border: 2px solid #e2e8f0;
                border-radius: 8px;
                font-size: 15px;
                background: white;
                color: #2d3748;
                font-family: inherit;
                transition: all 0.3s ease;
                margin-top: 10px;
            }

            input[type="number"]:focus {
                outline: none;
                border-color: #667eea;
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
                background: #f7fafc;
            }

            input[type="number"]:hover {
                border-color: #cbd5e0;
            }

            /* ========================================
            SELECT
            ======================================== */
            select {
                width: 100%;
                padding: 12px 15px;
                border: 2px solid #e2e8f0;
                border-radius: 8px;
                font-size: 15px;
                background: white;
                color: #2d3748;
                cursor: pointer;
                transition: all 0.3s ease;
                margin-bottom: 15px;
            }

            select:focus {
                outline: none;
                border-color: #667eea;
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            }

            select:hover {
                border-color: #cbd5e0;
            }

            select option:disabled {
                color: #cbd5e0;
                font-style: italic;
            }

            /* ========================================
            CHECKBOX
            ======================================== */
            label {
                display: flex;
                align-items: center;
                gap: 10px;
                color: #2d3748;
                font-size: 16px;
                font-weight: 500;
                cursor: pointer;
                padding: 10px;
                border-radius: 8px;
                transition: all 0.3s ease;
            }

            label:hover {
                background: rgba(102, 126, 234, 0.05);
            }

            input[type="checkbox"] {
                width: 20px;
                height: 20px;
                cursor: pointer;
                accent-color: #667eea;
            }

            /* ========================================
            BOTONES
            ======================================== */
            input[type="submit"] {
                width: 100%;
                padding: 14px 25px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                border-radius: 10px;
                color: white;
                font-size: 16px;
                font-weight: 600;
                text-transform: uppercase;
                cursor: pointer;
                transition: all 0.3s ease;
                letter-spacing: 0.8px;
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
                margin-top: 10px;
            }

            input[type="submit"]:hover {
                background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
            }

            input[type="submit"]:active {
                transform: translateY(0);
            }

            /* Botón de agregar producto (estilo diferenciado) */
            .btn-agregar {
                background: linear-gradient(135deg, #48bb78 0%, #38a169 100%) !important;
                box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3) !important;
            }

            .btn-agregar:hover {
                background: linear-gradient(135deg, #38a169 0%, #2f855a 100%) !important;
                box-shadow: 0 6px 20px rgba(72, 187, 120, 0.5) !important;
            }

            /* ========================================
            BOTÓN ELIMINAR (dentro de producto-item)
            ======================================== */
            .btn-eliminar {
                display: inline-block;
                padding: 8px 16px;
                background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
                color: white;
                text-decoration: none;
                border-radius: 6px;
                font-size: 13px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                transition: all 0.3s ease;
                box-shadow: 0 3px 10px rgba(245, 101, 101, 0.3);
            }

            .btn-eliminar:hover {
                background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(245, 101, 101, 0.5);
            }

            /* ========================================
            ENLACE VOLVER
            ======================================== */
            body > a {
                display: inline-block;
                margin: 0 auto 30px auto;
                padding: 12px 30px;
                color: white;
                text-decoration: none;
                font-size: 16px;
                font-weight: 600;
                background: rgba(255, 255, 255, 0.2);
                border: 2px solid white;
                border-radius: 10px;
                transition: all 0.3s ease;
                backdrop-filter: blur(10px);
                text-transform: uppercase;
                letter-spacing: 0.5px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            }

            body > a:hover {
                background: white;
                color: #667eea;
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(255, 255, 255, 0.3);
            }

            /* Centrar el enlace */
            body {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            /* ========================================
            ESTILOS DEL TOTAL
            ======================================== */
            form > p > strong {
                color: #2c3e50;
                font-size: 20px;
                display: block;
                text-align: right;
                margin: 20px 0;
                padding: 15px;
                background: rgba(102, 126, 234, 0.1);
                border-radius: 8px;
                border-left: 4px solid #667eea;
            }

            /* ========================================
            AJUSTES DE LAYOUT
            ======================================== */
            /* Hacer que h3 dentro del body (no en form) sea blanco */
            body > h3 {
                color: white;
                text-align: center;
                font-size: 28px;
                margin: 20px auto;
                text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
                max-width: 800px;
                padding: 15px;
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border-radius: 10px;
                border: none;
            }

            /* ========================================
            RESPONSIVE
            ======================================== */
            @media (max-width: 768px) {
                body {
                    padding: 20px 10px;
                }
                
                h1 {
                    font-size: 28px;
                    margin-bottom: 15px;
                }
                
                h3 {
                    font-size: 20px;
                }
                
                form {
                    padding: 20px;
                    margin: 15px 10px;
                }
                
                .producto-item {
                    padding: 15px;
                }
                
                .producto-item strong {
                    font-size: 16px;
                }
                
                input[type="number"] {
                    max-width: 100%;
                }
                
                .btn-eliminar {
                    float: none !important;
                    display: block;
                    margin: 10px 0;
                    text-align: center;
                }
            }

            @media (max-width: 480px) {
                h1 {
                    font-size: 24px;
                }
                
                body > a {
                    width: calc(100% - 40px);
                    text-align: center;
                    margin: 10px 20px;
                }
                
                form {
                    margin: 15px 5px;
                }
                
                input[type="submit"] {
                    padding: 12px;
                    font-size: 14px;
                }
            }

            /* ========================================
            ANIMACIONES Y EFECTOS ADICIONALES
            ======================================== */
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .producto-item {
                animation: fadeIn 0.4s ease;
            }

            /* Efecto de focus visible para accesibilidad */
            *:focus-visible {
                outline: 3px solid rgba(102, 126, 234, 0.5);
                outline-offset: 2px;
            }

            /* Deshabilitar inputs con estilo especial */
            input:disabled,
            select:disabled {
                background-color: #f1f5f9;
                color: #94a3b8;
                cursor: not-allowed;
                opacity: 0.6;
            }

            /* Placeholder mejorado */
            ::placeholder {
                color: #a0aec0;
                font-style: italic;
                opacity: 0.8;
            }

            /* Scrollbar personalizado (Chrome/Safari) */
            ::-webkit-scrollbar {
                width: 10px;
            }

            ::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.1);
            }

            ::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.3);
                border-radius: 5px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: rgba(255, 255, 255, 0.5);
}
        </style>
    </head>
    <body>
        <h1>Modificar datos de la venta</h1>
        <p id="comentario"></p>
        <a href="../gVentas.php">Volver</a>
        
        <?php
        // Mostrar mensajes
        if (isset($_SESSION['mensaje_error'])) {
            echo '<script>
                document.getElementById("comentario").style.color = "red";
                document.getElementById("comentario").innerHTML = "' . addslashes($_SESSION['mensaje_error']) . '";
            </script>';
            unset($_SESSION['mensaje_error']);
        } elseif (isset($_SESSION['mensaje_exito'])) {
            echo '<script>
                document.getElementById("comentario").style.color = "green";
                document.getElementById("comentario").innerHTML = "' . addslashes($_SESSION['mensaje_exito']) . '";
            </script>';
            unset($_SESSION['mensaje_exito']);
        }
        
        echo "<h3>Venta #" . $idVenta . "</h3>";
        echo "<p>Fecha: " . $venta['fecha_venta'] . "</p>";
        echo "<p>Estado: " . ($venta['estado'] == 4 ? 'Entregado' : 'Pendiente') . "</p>";
        echo "<hr>";
        ?>
        
        <form action="" method="post">
            <h3>Productos de esta venta:</h3>
            
            <?php 
            $totalGeneral = 0;
            while($prod = mysqli_fetch_assoc($resultProductos)): 
                $totalGeneral += $prod['total'];
            ?>
                <div class="producto-item">
                    <strong><?php echo $prod['nombre']; ?></strong>
                    <a href="?id=<?php echo $idVenta; ?>&eliminar_prod=<?php echo $prod['id']; ?>" class="btn-eliminar" onclick="return confirm('¿Seguro? Se devolverán <?php echo $prod['cantidad']; ?> unidades al stock');"style="float: right;">Eliminar
                    </a>
                    <br><br> Cantidad actual: <?php echo $prod['cantidad']; ?><br><small>Stock disponible: <?php echo $prod['stock_disponible']; ?></small><br>Total: $<?php echo $prod['total']; ?><br><br>
                    
                    Nueva cantidad: 
                    <input type="number" name="cantidad[<?php echo $prod['id']; ?>]" value="<?php echo $prod['cantidad']; ?>" min="1" step="1" required>
                </div>
            <?php endwhile; ?>
            
            <p><strong>Total: $<?php echo number_format($totalGeneral, 2); ?></strong></p>
            
            <label>
                <input type="checkbox" name="entregado" value="1" <?php echo ($venta['estado'] == 4) ? 'checked' : ''; ?>>Marcar como Entregado
            </label>
            <br><br>
            
            <input type="hidden" name="idVenta" value="<?php echo $idVenta; ?>">
            <input type="submit" name="btnEnv" value="Guardar Cambios">
        </form>
        
        <hr>
        <h3>Agregar producto</h3>
        <form action="" method="post">
            <select name="nuevo_producto" required>
                <option value="">-- Seleccione --</option>
                <?php
                    $resultProdDisp = mysqli_query($link,"SELECT id, nombre, precio, cantidad FROM producto WHERE estado = 1");
                    
                    while($prodDisp = mysqli_fetch_assoc($resultProdDisp)) {
                        $disponible = $prodDisp['cantidad'] > 0 ? "(" . $prodDisp['cantidad'] . " disponibles)" : "(SIN STOCK)";
                        $disabled = $prodDisp['cantidad'] == 0 ? "disabled" : "";
                        
                        echo '<option value="' . $prodDisp['id'] . '" ' . $disabled . '>' . $prodDisp['nombre'] . ' - $' . $prodDisp['precio'] . ' ' . $disponible . '</option>';
                    }
                ?>
            </select>
            
            <input type="number" name="nueva_cantidad" placeholder="Cantidad" min="1" step="1" required>
            <input type="submit" name="btnAgregarProd" value="Agregar">
        </form>
        
    </body>
</html>