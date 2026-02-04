<?php
// ⭐ TODO EL PHP PRIMERO (antes de cualquier HTML)
include '../../../BD/conex.php';
session_start();

// Obtener ID de la compra
if(!isset($_GET['id'])) {
    die("No se proporcionó un ID de compra");
}

$idCompra = intval($_GET['id']);

// ⭐ Eliminar producto (CON HEADER - debe estar antes del HTML)
if(isset($_GET['eliminar_prod'])) {
    $idProductoEliminar = intval($_GET['eliminar_prod']);
    mysqli_query($link, "DELETE FROM producto_compra WHERE id = $idProductoEliminar AND idCompra = $idCompra");
    $_SESSION['mensaje'] = "Producto eliminado de la compra";
    header("Location: ?id=$idCompra");
    exit;
}

// ⭐ Agregar nuevo producto (CON HEADER - debe estar antes del HTML)
if(isset($_POST['btnAgregarProd']) && !empty($_POST['nuevo_producto']) && !empty($_POST['nueva_cantidad'])) {
    $idProductoNuevo = intval($_POST['nuevo_producto']);
    $cantidadNueva = intval($_POST['nueva_cantidad']);
    
    $verificar = mysqli_query($link,"SELECT * FROM producto_compra WHERE idCompra = $idCompra AND idProd = $idProductoNuevo");
    
    if(mysqli_num_rows($verificar) > 0) {
        $_SESSION['mensaje'] = "Este producto ya está en la compra. Edita su cantidad en la lista.";
    } else {
        $resultPrecio = mysqli_query($link, "SELECT precio FROM producto WHERE id = $idProductoNuevo");
        $producto = mysqli_fetch_assoc($resultPrecio);
        $totalNuevo = $cantidadNueva * $producto['precio'];
        
        mysqli_query($link,
            "INSERT INTO producto_compra (idProd, idCompra, cantidad, total) VALUES ($idProductoNuevo, $idCompra, $cantidadNueva, $totalNuevo)");
        
        $_SESSION['mensaje'] = "Producto agregado exitosamente";
    }
    
    header("Location: ?id=$idCompra");
    exit;
}

// ⭐ Procesar actualización (CON HEADER - debe estar antes del HTML)
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btnEnv'])) {
    $idCompra = intval($_POST['idCompra']);
    
    if(isset($_POST['cantidad']) && is_array($_POST['cantidad'])) {
        foreach($_POST['cantidad'] as $idProductoCompra => $nuevaCantidad) {
            $idProductoCompra = intval($idProductoCompra);
            $nuevaCantidad = intval($nuevaCantidad);
            
            if($nuevaCantidad > 0) {
                $resultPrecio = mysqli_query($link,"SELECT producto.precio FROM producto_compra JOIN producto ON producto.id = producto_compra.idProd WHERE producto_compra.id = $idProductoCompra");
                $precio = mysqli_fetch_assoc($resultPrecio);
                $nuevoTotal = $nuevaCantidad * $precio['precio'];
                
                mysqli_query($link,"UPDATE producto_compra SET cantidad = $nuevaCantidad, total = $nuevoTotal WHERE id = $idProductoCompra");
            }
        }
    }
    
    $nuevoEstado = isset($_POST['entregado']) ? 4 : 3;
    mysqli_query($link,"UPDATE compra SET estado = $nuevoEstado WHERE id = $idCompra");
    
    $_SESSION['mensaje'] = "¡Compra actualizada exitosamente!";
    header("Location: modificarCompra.php");
    exit;
}

// ⭐ Obtener datos de la compra (DESPUÉS de procesar todo)
$resultCompra = mysqli_query($link, "SELECT * FROM compra WHERE id = $idCompra");
$compra = mysqli_fetch_assoc($resultCompra);

if(!$compra) {
    die("No se encontró la compra");
}

// Obtener proveedor
$resultProv = mysqli_query($link,
    "SELECT DISTINCT producto.idP FROM producto_compra JOIN producto ON producto.id = producto_compra.idProd WHERE producto_compra.idCompra = $idCompra LIMIT 1");
$provData = mysqli_fetch_assoc($resultProv);
$idProveedor = $provData ? $provData['idP'] : null;

// Obtener productos de esta compra
$resultProductos = mysqli_query($link,
    "SELECT producto_compra.id, producto.nombre, producto_compra.cantidad, producto_compra.total, producto.precio
     FROM producto_compra 
     JOIN producto ON producto.id = producto_compra.idProd 
     WHERE producto_compra.idCompra = $idCompra");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Modificar Compra Seleccionada</title>
        <link rel="stylesheet" href="estiloCambiarDatosC.css">
    </head>
    <body>
        <h1>Modificar datos de la compra</h1>
        <p id="comentario">
            <?php 
            if(isset($_SESSION['mensaje'])) {
                echo $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);
            }
            ?>
        </p>
        
        <div class="compra-info-header">
            <h3>Compra #<?php echo $idCompra; ?></h3>
            <p><strong>Fecha:</strong> <?php echo $compra['fecha_compra']; ?></p>
            <p><strong>Estado actual:</strong> 
                <span class="badge-estado <?php echo ($compra['estado'] == 4 ? 'recibido' : 'pedido'); ?>">
                    <?php echo ($compra['estado'] == 4 ? 'Recibido' : 'Pedido'); ?>
                </span>
            </p>
        </div>
        
        <form action="" method="post" class="form-principal">
            <h3>Productos de esta compra:</h3>
            
            <div class="productos-container">
                <?php 
                $totalGeneral = 0;
                while($prod = mysqli_fetch_assoc($resultProductos)): 
                    $totalGeneral += $prod['total'];
                ?>
                    <div class="producto-edit-item">
                        <div class="producto-header">
                            <strong class="producto-nombre"><?php echo $prod['nombre']; ?></strong>
                            <a href="?id=<?php echo $idCompra; ?>&eliminar_prod=<?php echo $prod['id']; ?>" 
                               class="btn-eliminar-prod" 
                               onclick="return confirm('¿Seguro que deseas eliminar este producto?');">
                                Eliminar
                            </a>
                        </div>
                        
                        <div class="producto-detalles">
                            <p><strong>Precio unitario:</strong> $<?php echo number_format($prod['precio'], 2); ?></p>
                            <p><strong>Cantidad actual:</strong> <?php echo $prod['cantidad']; ?></p>
                            <p><strong>Total:</strong> $<?php echo number_format($prod['total'], 2); ?></p>
                        </div>
                        
                        <div class="input-group">
                            <label>Nueva cantidad:</label>
                            <input type="number" name="cantidad[<?php echo $prod['id']; ?>]" value="<?php echo $prod['cantidad']; ?>" min="1" step="1" required>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            
            <div class="total-general">
                <strong>Total de la compra: $<?php echo number_format($totalGeneral, 2); ?></strong>
            </div>
            
            <div class="checkbox-container">
                <label class="checkbox-label">
                    <input type="checkbox" name="entregado" value="1" 
                           <?php echo ($compra['estado'] == 4) ? 'checked' : ''; ?>>
                    <span>Marcar como Recibido</span>
                </label>
            </div>
            
            <input type="hidden" name="idCompra" value="<?php echo $idCompra; ?>">
            <button type="submit" name="btnEnv" class="btn-guardar"> Guardar Cambios</button>
        </form>
        
        <!-- Formulario para agregar producto -->
        <div class="agregar-producto-section">
            <h3> Agregar producto a esta compra</h3>
            <form action="" method="post" class="form-agregar">
                <div class="form-row">
                    <select name="nuevo_producto" required>
                        <option value="">-- Seleccione un producto --</option>
                        <?php
                        if($idProveedor) {
                            $resultProdDisp = mysqli_query($link,"SELECT id, nombre, precio FROM producto WHERE estado = 1 AND idP = $idProveedor");
                            
                            while($prodDisp = mysqli_fetch_assoc($resultProdDisp)) {
                                echo '<option value="' . $prodDisp['id'] . '">' 
                                     . $prodDisp['nombre'] . ' - $' . number_format($prodDisp['precio'], 2)
                                     . '</option>';
                            }
                        }
                        ?>
                    </select>
                    
                    <input type="number" name="nueva_cantidad" placeholder="Cantidad" min="1" step="1" required>
                    <button type="submit" name="btnAgregarProd" class="btn-agregar">+ Agregar</button>
                </div>
            </form>
        </div>
        
        <a href="modificarCompra.php" class="btn-volver">← Volver</a>
    </body>
</html>