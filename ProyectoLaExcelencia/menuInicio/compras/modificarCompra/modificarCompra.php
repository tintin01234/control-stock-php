<?php
// Configuración inicial
include '../../../BD/conex.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Modificar datos/estado de la compra</title>
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
   COMENTARIOS
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
    max-width: 700px;
}

#comentario[style*="color: red"] {
    background: rgba(245, 101, 101, 0.2);
    border: 2px solid rgba(245, 101, 101, 0.5);
    color: #fff5f5 !important;
    box-shadow: 0 4px 15px rgba(245, 101, 101, 0.3);
}

/* ========================================
   CONTENEDOR DE CONTROLES
   ======================================== */
.controles-container {
    display: flex;
    gap: 15px;
    width: 100%;
    max-width: 700px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

/* ========================================
   FORMULARIO DE BÚSQUEDA
   ======================================== */
.form-buscar {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(15px);
    border-radius: 15px;
    padding: 20px 25px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    flex: 1;
    min-width: 300px;
    display: flex;
    gap: 10px;
}

input[type="number"] {
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

input[type="number"]:focus {
    outline: none;
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

input[type="number"]::placeholder {
    color: #a0aec0;
    font-style: italic;
}

.btn-buscar {
    padding: 12px 25px;
    background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
    border: none;
    border-radius: 10px;
    color: white;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-buscar:hover {
    background: linear-gradient(135deg, #3182ce 0%, #2c5282 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(66, 153, 225, 0.4);
}

/* ========================================
   BOTÓN MOSTRAR ELIMINADOS
   ======================================== */
.form-eliminados {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(15px);
    border-radius: 15px;
    padding: 20px 25px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-mostrar-eliminados {
    padding: 12px 25px;
    background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
    border: none;
    border-radius: 10px;
    color: white;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.3s ease;
    letter-spacing: 0.5px;
}

.btn-mostrar-eliminados:hover {
    background: linear-gradient(135deg, #dd6b20 0%, #c05621 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(237, 137, 54, 0.4);
}

/* ========================================
   SECCIÓN DE ELIMINADOS
   ======================================== */
.seccion-eliminados {
    width: 100%;
    max-width: 700px;
    margin-top: 40px;
    padding-top: 30px;
    border-top: 3px solid rgba(255, 255, 255, 0.3);
}

.titulo-eliminados {
    color: white;
    font-size: 28px;
    font-weight: 700;
    text-align: center;
    margin-bottom: 25px;
    text-shadow: 0 3px 15px rgba(0, 0, 0, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.titulo-eliminados::before,
.titulo-eliminados::after {
    content: '';
    height: 2px;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
    flex: 1;
}

/* ========================================
   TARJETA DE COMPRA
   ======================================== */
.compra-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(15px);
    border-radius: 15px;
    padding: 25px 30px;
    margin-bottom: 25px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.3);
    width: 100%;
    max-width: 700px;
    transition: all 0.3s ease;
    border-left: 5px solid #9f7aea;
}

.compra-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
}

.compra-card-eliminada {
    border-left: 5px solid #f56565;
    opacity: 0.9;
}

.compra-header {
    color: #1a202c;
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 10px;
}

.compra-info {
    color: #2d3748;
    font-size: 15px;
    line-height: 1.8;
    margin-bottom: 8px;
}

.compra-info strong {
    color: #1a202c;
    font-weight: 600;
}

.estado-pedido {
    display: inline-block;
    padding: 4px 12px;
    background: rgba(237, 137, 54, 0.2);
    color: #dd6b20;
    border-radius: 5px;
    font-size: 14px;
    font-weight: 600;
    margin-left: 5px;
}

.estado-recibido {
    display: inline-block;
    padding: 4px 12px;
    background: rgba(72, 187, 120, 0.2);
    color: #38a169;
    border-radius: 5px;
    font-size: 14px;
    font-weight: 600;
    margin-left: 5px;
}

.estado-eliminado {
    display: inline-block;
    padding: 4px 12px;
    background: rgba(245, 101, 101, 0.2);
    color: #e53e3e;
    border-radius: 5px;
    font-size: 14px;
    font-weight: 600;
    margin-left: 5px;
}

.productos-lista {
    background: rgba(102, 126, 234, 0.05);
    padding: 15px;
    border-radius: 10px;
    margin: 15px 0;
}

.producto-item {
    color: #2d3748;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 12px;
    padding-left: 10px;
    border-left: 3px solid #9f7aea;
}

.producto-item:last-child {
    margin-bottom: 0;
}

/* ========================================
   BOTONES
   ======================================== */
.btn-editar {
    display: inline-block !important;
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
    margin-top: 10px !important;
    margin-right: 10px !important;
}

.btn-editar:hover {
    background: linear-gradient(135deg, #3182ce 0%, #2c5282 100%) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 18px rgba(66, 153, 225, 0.5) !important;
}

.btn-eliminar {
    display: inline-block !important;
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
    margin-top: 10px !important;
}

.btn-eliminar:hover {
    background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 18px rgba(245, 101, 101, 0.5) !important;
}

/* ========================================
   ENLACE VOLVER
   ======================================== */
.btn-volver {
    display: inline-block;
    margin-bottom: 30px;
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
}

.btn-volver:hover {
    background: white;
    color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3);
}

/* RESPONSIVE */
@media (max-width: 768px) {
    h1 { font-size: 30px; }
    .compra-card { max-width: 90%; padding: 20px; }
    .controles-container { flex-direction: column; }
    .form-buscar { min-width: 100%; flex-direction: column; }
    .btn-buscar { width: 100%; }
}

@media (max-width: 480px) {
    h1 { font-size: 24px; }
    .compra-card { max-width: 100%; }
    .btn-editar, .btn-eliminar {
        display: block !important;
        width: 100% !important;
        margin-right: 0 !important;
        margin-bottom: 8px !important;
    }
}
        </style>
    </head>
    <body>
        <h1>Gestionar Compras</h1>
        <a href="../gCompras.php" class="btn-volver">Volver</a>
        <p id="comentario"></p>
        
        <!-- Contenedor de controles: Búsqueda y Mostrar Eliminados -->
        <div class="controles-container">
            <form action="" method="get" class="form-buscar">
                <input type="number" name="buscar" placeholder="Buscar por ID de compra..." value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>">
                <input type="submit" value="Buscar" class="btn-buscar">
            </form>
            
            <form action="" method="post" class="form-eliminados">
                <input type="submit" name="btnMos" value="Mostrar Eliminados" class="btn-mostrar-eliminados">
            </form>
        </div>
        
        <?php 
        // BÚSQUEDA POR ID
        if(isset($_GET['buscar']) && !empty($_GET['buscar'])) {
            $busqueda = intval($_GET['buscar']);
            $resultado = mysqli_query($link, "SELECT * FROM compra WHERE id = $busqueda ORDER BY id DESC");
            
            if(mysqli_num_rows($resultado) > 0) {
                echo "<h3>Resultados de búsqueda:</h3>";
                while($verRes = mysqli_fetch_array($resultado)) {
                    $idCompra = $verRes['id'];
                    $claseEliminado = ($verRes['estado'] == 2) ? 'compra-card-eliminada' : '';
                    
                    echo '<div class="compra-card ' . $claseEliminado . '">';
                    echo '<p class="compra-header">Compra #' . $idCompra . '</p>';
                    echo '<p class="compra-info"><strong>Fecha:</strong> ' . $verRes['fecha_compra'] . '</p>';
                    
                    if($verRes['estado'] == 3) {
                        echo '<p class="compra-info"><strong>Estado:</strong> <span class="estado-pedido">Pedido</span></p>';
                    } elseif($verRes['estado'] == 4) {
                        echo '<p class="compra-info"><strong>Estado:</strong> <span class="estado-recibido">Recibido</span></p>';
                    } else {
                        echo '<p class="compra-info"><strong>Estado:</strong> <span class="estado-eliminado">Eliminado</span></p>';
                    }

                    $resProd = mysqli_query($link,
                        "SELECT producto.nombre, producto_compra.cantidad, producto_compra.total 
                        FROM producto 
                        JOIN producto_compra ON producto.id = producto_compra.idProd 
                        WHERE producto_compra.idCompra = $idCompra");

                    echo '<div class="productos-lista">';
                    while($productos = mysqli_fetch_array($resProd)) {
                        echo '<div class="producto-item">';
                        echo '<strong>Producto:</strong> ' . $productos[0] . '<br>';
                        echo '<strong>Cantidad:</strong> ' . $productos[1] . '<br>';
                        echo '<strong>Total:</strong> $' . number_format($productos[2], 2);
                        echo '</div>';
                    }
                    echo '</div>';
                    if($verRes['estado'] == 4 || $verRes['estado'] == 2) {
                        echo '</div>';
                    } else {
                        echo "<a href='cambiarDatosC.php?id=" . $idCompra . "' class='btn-editar'>Editar</a>";
                        echo "<a href='eliminarC.php?id=" . $idCompra . "' class='btn-eliminar'>Eliminar</a>";
                        echo '</div>';
                    }
                }
            } else {
                echo '<script>
                            document.getElementById("comentario").style.color = "red";
                            document.getElementById("comentario").innerHTML = "No se encontró la compra con ese ID";
                    </script>';
            }
        } 
        // MOSTRAR COMPRAS ACTIVAS (NO ELIMINADAS)
        else {
            $resultadoCompras = mysqli_query($link,"SELECT * FROM compra WHERE estado != 2 ORDER BY estado ASC, id DESC");
            while($verRes = mysqli_fetch_array($resultadoCompras)) {
                $idCompra = $verRes['id'];
                echo '<div class="compra-card">';
                echo '<p class="compra-header">Compra #' . $idCompra . '</p>';
                echo '<p class="compra-info"><strong>Fecha:</strong> ' . $verRes['fecha_compra'] . '</p>';
                
                if($verRes['estado'] == 3) {
                    echo '<p class="compra-info"><strong>Estado:</strong> <span class="estado-pedido">Pedido</span></p>';
                } elseif($verRes['estado'] == 4) {
                    echo '<p class="compra-info"><strong>Estado:</strong> <span class="estado-recibido">Recibido</span></p>';
                }

                $resProd = mysqli_query($link,
                    "SELECT producto.nombre, producto_compra.cantidad, producto_compra.total 
                    FROM producto 
                    JOIN producto_compra ON producto.id = producto_compra.idProd 
                    WHERE producto_compra.idCompra = $idCompra");

                echo '<div class="productos-lista">';
                while($productos = mysqli_fetch_array($resProd)) {
                    echo '<div class="producto-item">';
                    echo '<strong>Producto:</strong> ' . $productos[0] . '<br>';
                    echo '<strong>Cantidad:</strong> ' . $productos[1] . '<br>';
                    echo '<strong>Total:</strong> $' . number_format($productos[2], 2);
                    echo '</div>';
                }
                echo '</div>';
                if($verRes['estado'] == 4 || $verRes['estado'] == 2) {
                    echo '</div>';
                } else {
                    echo "<a href='cambiarDatosC.php?id=" . $idCompra . "' class='btn-editar'>Editar</a>";
                    echo "<a href='eliminarC.php?id=" . $idCompra . "' class='btn-eliminar'>Eliminar</a>";
                    echo '</div>';
                }
                
            }
        }
        
        if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btnMos'])) {
            echo '<div class="seccion-eliminados">';
            echo '<h2 class="titulo-eliminados">Compras Eliminadas</h2>';
            
            $resultadoCompras = mysqli_query($link,"SELECT * FROM compra WHERE estado = 2 ORDER BY id DESC");
            
            if(mysqli_num_rows($resultadoCompras) > 0) {
                while($verRes = mysqli_fetch_array($resultadoCompras)) {
                    $idCompra = $verRes['id'];
                    echo '<div class="compra-card compra-card-eliminada">';
                    echo '<p class="compra-header">Compra #' . $idCompra . '</p>';
                    echo '<p class="compra-info"><strong>Fecha:</strong> ' . $verRes['fecha_compra'] . '</p>';
                    echo '<p class="compra-info"><strong>Estado:</strong> <span class="estado-eliminado">Eliminado</span></p>';
                    
                    $resProd = mysqli_query($link,
                        "SELECT producto.nombre, producto_compra.cantidad, producto_compra.total 
                        FROM producto 
                        JOIN producto_compra ON producto.id = producto_compra.idProd 
                        WHERE producto_compra.idCompra = $idCompra");

                    echo '<div class="productos-lista">';
                    while($productos = mysqli_fetch_array($resProd)) {
                        echo '<div class="producto-item">';
                        echo '<strong>Producto:</strong> ' . $productos[0] . '<br>';
                        echo '<strong>Cantidad:</strong> ' . $productos[1] . '<br>';
                        echo '<strong>Total:</strong> $' . number_format($productos[2], 2);
                        echo '</div>';
                    }
                    echo '</div>';
                    echo '</div>';
                    
                }
            } else {
                echo '<p style="color: white; text-align: center; font-size: 16px; margin-top: 20px;">No hay compras eliminadas</p>';
            }
            
            echo '</div>';
        }
        ?>
        
    </body>
</html>