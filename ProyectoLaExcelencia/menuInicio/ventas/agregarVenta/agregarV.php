<?php
// ============================================
// SECCIÓN 1: CONFIGURACIÓN INICIAL
// ============================================
include '../../../BD/conex.php';
session_start();

// Inicializar carrito de VENTA si no existe
if(!isset($_SESSION['carrito_venta'])) {
    $_SESSION['carrito_venta'] = array();
}

// ============================================
// SECCIÓN 2: PROCESAMIENTO DE ACCIONES
// ============================================

$mensajeExito = '';
$mensajeError = '';

// Agregar producto al carrito de VENTA
if(isset($_POST['btnAgregarProd'])) {
    $idProd = intval($_POST['selProd']);
    $cantidad = intval($_POST['cant']);
    
    if($cantidad > 0) {
        $consultaP = mysqli_query($link, "SELECT nombre, precio, cantidad as stock FROM producto WHERE id = $idProd");
        $producto = mysqli_fetch_assoc($consultaP);
        
        // Verificar stock disponible (considerando lo que ya está en el carrito)
        $cantidadEnCarrito = isset($_SESSION['carrito_venta'][$idProd]) ? $_SESSION['carrito_venta'][$idProd]['cantidad'] : 0;
        $cantidadTotal = $cantidadEnCarrito + $cantidad;
        
        if($cantidadTotal > $producto['stock']) {
            $_SESSION['mensaje_error'] = "Stock insuficiente. Solo hay " . $producto['stock'] . " unidades de " . $producto['nombre'] . " disponibles.";
        } else {
            $subtotal = $cantidad * $producto['precio'];
            
            if(isset($_SESSION['carrito_venta'][$idProd])) {
                $_SESSION['carrito_venta'][$idProd]['cantidad'] += $cantidad;
                $_SESSION['carrito_venta'][$idProd]['subtotal'] = $_SESSION['carrito_venta'][$idProd]['cantidad'] * $producto['precio'];
            } else {
                $_SESSION['carrito_venta'][$idProd] = array(
                    'nombre' => $producto['nombre'],
                    'precio' => $producto['precio'],
                    'cantidad' => $cantidad,
                    'subtotal' => $subtotal
                );
            }
            
            $_SESSION['mensaje_exito'] = "✓ Producto agregado al carrito";
        }
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Eliminar producto del carrito de VENTA
if(isset($_GET['eliminar'])) {
    $idEliminar = intval($_GET['eliminar']);
    
    if(isset($_SESSION['carrito_venta'][$idEliminar])) {
        $nombreEliminado = $_SESSION['carrito_venta'][$idEliminar]['nombre'];
        unset($_SESSION['carrito_venta'][$idEliminar]);
        $_SESSION['mensaje_exito'] = "✓ Producto eliminado: " . $nombreEliminado;
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Finalizar venta
if(isset($_POST['btnFinalizarVenta']) && !empty($_SESSION['carrito_venta'])) {
    $errorStock = false;
    $mensajesStock = array();
    
    // Verificar stock para todos los productos del carrito
    foreach($_SESSION['carrito_venta'] as $idProd => $item) {
        $verProd = mysqli_query($link, "SELECT nombre, cantidad FROM producto WHERE id = $idProd");
        $verstock = mysqli_fetch_array($verProd);
        
        if($verstock['cantidad'] < $item['cantidad']) {
            $errorStock = true;
            $mensajesStock[] = "Stock insuficiente de " . $verstock['nombre'] . ". Disponible: " . $verstock['cantidad'] . " unidades.";
        }
    }
    
    if($errorStock) {
        $_SESSION['mensaje_error'] = implode(" ", $mensajesStock);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        // Crear la venta
        $crearVenta = mysqli_query($link, "INSERT INTO venta(fecha_venta, estado) VALUES(NOW(), 3)");
        $idVenta = mysqli_insert_id($link);
        
        // Insertar cada producto (el trigger resta el stock automáticamente)
        foreach($_SESSION['carrito_venta'] as $idProd => $item) {
            $cantidad = $item['cantidad'];
            $total = $item['subtotal'];
            
            $pedidoVenta = mysqli_query($link,
                "INSERT INTO producto_venta(idProd, idVenta, cantidad, total) 
                 VALUES($idProd, $idVenta, $cantidad, $total)");
        }
        if(isset($_POST['ent'])) {
                   $est =  mysqli_query($link,"UPDATE venta SET estado = 5 where id = $idVenta ");
                }
        // Limpiar carrito de VENTA
        $_SESSION['carrito_venta'] = array();
        
        header("Location: ../gVentas.php?mensaje=Venta registrada exitosamente");
        exit;
    }
}

// Obtener mensajes de sesión
if(isset($_SESSION['mensaje_exito'])) {
    $mensajeExito = $_SESSION['mensaje_exito'];
    unset($_SESSION['mensaje_exito']);
}
if(isset($_SESSION['mensaje_error'])) {
    $mensajeError = $_SESSION['mensaje_error'];
    unset($_SESSION['mensaje_error']);
}

// ============================================
// SECCIÓN 3: HTML
// ============================================
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Nueva Venta</title>
        <style>
        /* Reseteo universal: elimina márgenes y rellenos predeterminados del navegador */
        * {
            margin: 0; /* Elimina todos los márgenes externos */
            padding: 0; /* Elimina todos los rellenos internos */
            box-sizing: border-box; /* Hace que el ancho/alto incluya padding y border */
        }

        /* Estilos del cuerpo principal de la página */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Fuente moderna y legible */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); /* Degradado de púrpura-azul */
            min-height: 100vh; /* Altura mínima = 100% de la ventana del navegador */
            padding: 20px; /* Espacio interior en todos los lados */
        }

        /* Estilo del título principal */
        h1 {
            text-align: center; /* Centra el texto horizontalmente */
            color: white; /* Color del texto blanco */
            margin-bottom: 30px; /* Espacio debajo del título */
            font-size: 2.5em; /* Tamaño de fuente grande (2.5 veces el tamaño base) */
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2); /* Sombra del texto para darle profundidad */
        }

        /* Estilos para subtítulos (h3) */
        h3 {
            color: white; /* Color blanco */
            background: rgba(255, 255, 255, 0.1); /* Fondo semi-transparente */
            padding: 15px; /* Relleno interno */
            border-radius: 10px; /* Bordes redondeados */
            text-align: center; /* Texto centrado */
            margin-bottom: 20px; /* Espacio debajo del subtítulo */
            font-size: 1.3em; /* Tamaño de fuente mediano */
            backdrop-filter: blur(10px); /* Efecto de desenfoque en el fondo */
        }

        /* Estilos para el mensaje de comentario/feedback */
        #comentario {
            text-align: center; /* Centra el texto */
            font-size: 1.1em; /* Tamaño ligeramente más grande */
            font-weight: bold; /* Texto en negrita */
            margin-bottom: 20px; /* Espacio debajo */
            min-height: 25px; /* Altura mínima para evitar saltos visuales */
            animation: fadeIn 0.5s ease; /* Aplica animación de aparición */
            padding: 10px; /* Relleno interno */
            border-radius: 8px; /* Bordes redondeados */
        }

        /* Estilo para mensajes de éxito */
        .mensaje-exito {
            color: white;
            background: rgba(72, 187, 120, 0.3);
            border: 2px solid rgba(72, 187, 120, 0.6);
        }

        /* Estilo para mensajes de error */
        .mensaje-error {
            color: white;
            background: rgba(245, 101, 101, 0.3);
            border: 2px solid rgba(245, 101, 101, 0.6);
        }

        /* Define la animación fadeIn (aparecer gradualmente) */
        @keyframes fadeIn {
            from { /* Estado inicial */
                opacity: 0; /* Completamente transparente */
                transform: translateY(-10px); /* Desplazado 10px hacia arriba */
            }
            to { /* Estado final */
                opacity: 1; /* Completamente visible */
                transform: translateY(0); /* En su posición original */
            }
        }

        /* Estilos para todos los formularios */
        form {
            background: white; /* Fondo blanco para destacar del fondo degradado */
            padding: 30px; /* Espacio interior de 30px en todos los lados */
            border-radius: 15px; /* Bordes redondeados de 15px */
            box-shadow: 0 8px 30px rgba(0,0,0,0.3); /* Sombra: desplazamiento vertical 8px, difuminado 30px */
            margin-bottom: 25px; /* Espacio debajo del formulario */
            transition: transform 0.3s ease; /* Transición suave para el efecto hover */
            max-width: 600px; /* Ancho máximo */
            margin-left: auto; /* Centrado horizontal */
            margin-right: auto; /* Centrado horizontal */
        }

        /* Efecto cuando pasas el cursor sobre el formulario */
        form:hover {
            transform: translateY(-3px); /* Eleva el formulario 3px hacia arriba */
            box-shadow: 0 12px 40px rgba(0,0,0,0.4); /* Sombra más pronunciada al hacer hover */
        }

        /* Estilos para etiquetas y texto dentro de formularios */
        form label,
        form > text {
            display: block; /* Hace que cada etiqueta ocupe toda la línea */
            color: #2c3e50; /* Color gris oscuro */
            font-weight: 600; /* Texto semi-negrita */
            margin-bottom: 10px; /* Espacio debajo de la etiqueta */
            font-size: 1.05em; /* Tamaño ligeramente más grande que el texto normal */
        }

        /* Estilos para los elementos select (menús desplegables) */
        select {
            width: 100%; /* Ocupa todo el ancho disponible */
            padding: 12px 15px; /* Relleno: 12px arriba/abajo, 15px izq/der */
            margin-bottom: 15px; /* Espacio debajo del select */
            border: 2px solid #e0e0e0; /* Borde gris claro de 2px */
            border-radius: 8px; /* Bordes redondeados */
            font-size: 1em; /* Tamaño de fuente normal */
            background-color: #f8f9fa; /* Fondo gris muy claro */
            color: #2c3e50; /* Color del texto gris oscuro */
            cursor: pointer; /* Cambia el cursor a manita al pasar sobre él */
            transition: all 0.3s ease; /* Transición suave para todos los cambios */
        }

        /* Estilos cuando el select está enfocado (al hacer clic) */
        select:focus {
            outline: none; /* Elimina el borde azul predeterminado del navegador */
            border-color: #667eea; /* Cambia el color del borde a púrpura */
            background-color: white; /* Fondo blanco al enfocar */
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); /* Sombra difuminada púrpura alrededor */
        }

        /* Estilos al pasar el cursor sobre el select */
        select:hover {
            border-color: #667eea; /* Borde púrpura al hacer hover */
        }

        /* Estilos para inputs de tipo número (campo de cantidad) */
        input[type="number"] {
            width: 100%; /* Ocupa todo el ancho */
            padding: 12px 15px; /* Relleno interno */
            margin-bottom: 15px; /* Espacio debajo */
            border: 2px solid #e0e0e0; /* Borde gris claro */
            border-radius: 8px; /* Bordes redondeados */
            font-size: 1em; /* Tamaño de fuente normal */
            background-color: #f8f9fa; /* Fondo gris muy claro */
            color: #2c3e50; /* Color del texto */
            transition: all 0.3s ease; /* Transición suave */
        }

        /* Cuando el input de número está enfocado */
        input[type="number"]:focus {
            outline: none; /* Quita el borde azul del navegador */
            border-color: #667eea; /* Borde púrpura */
            background-color: white; /* Fondo blanco */
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); /* Resplandor púrpura suave */
        }

        /* Estilos para todos los botones submit */
        input[type="submit"] {
            width: 100%; /* Ocupa todo el ancho disponible */
            padding: 14px; /* Relleno interno de 14px */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); /* Degradado púrpura */
            color: white; /* Texto blanco */
            border: none; /* Sin borde */
            border-radius: 8px; /* Bordes redondeados */
            font-size: 1.1em; /* Tamaño de fuente más grande */
            font-weight: 600; /* Texto semi-negrita */
            cursor: pointer; /* Cursor de manita */
            transition: all 0.3s ease; /* Transición suave para todos los efectos */
            text-transform: uppercase; /* Convierte el texto a mayúsculas */
            letter-spacing: 0.5px; /* Espacio entre letras de 0.5px */
        }

        /* Efecto hover sobre los botones */
        input[type="submit"]:hover {
            transform: translateY(-2px); /* Eleva el botón 2px hacia arriba */
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4); /* Sombra púrpura difuminada */
        }

        /* Efecto al hacer clic en el botón */
        input[type="submit"]:active {
            transform: translateY(0); /* Vuelve a su posición original (sensación de "presionar") */
        }

        /* Estilo específico para el botón de finalizar venta (verde) */
        input[name="btnFinalizarVenta"] {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); /* Degradado verde */
            margin-top: 20px; /* Espacio adicional arriba para separarlo */
            font-size: 1.2em; /* Tamaño más grande para destacar */
            padding: 16px; /* Más relleno que los otros botones */
        }

        /* Hover del botón de finalizar venta */
        input[name="btnFinalizarVenta"]:hover {
            box-shadow: 0 6px 20px rgba(17, 153, 142, 0.4); /* Sombra verde difuminada */
        }

        /* Estilos para la tabla del carrito de compras */
        table {
            width: 100%; /* Ocupa todo el ancho disponible */
            background: white; /* Fondo blanco */
            border-radius: 12px; /* Bordes redondeados */
            overflow: hidden; /* Oculta contenido que sobresale (importante para border-radius) */
            box-shadow: 0 8px 30px rgba(0,0,0,0.3); /* Sombra para profundidad */
            border-collapse: collapse; /* Elimina espacios entre celdas */
            margin: 20px auto; /* Centrado con margen arriba/abajo */
            max-width: 800px; /* Ancho máximo */
        }

        /* Estilos para los encabezados de la tabla */
        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); /* Degradado púrpura */
            color: white; /* Texto blanco */
            padding: 15px; /* Relleno interno */
            text-align: left; /* Alinea el texto a la izquierda */
            font-weight: 600; /* Texto semi-negrita */
            text-transform: uppercase; /* Convierte a mayúsculas */
            letter-spacing: 0.5px; /* Espaciado entre letras */
            font-size: 0.95em; /* Tamaño ligeramente más pequeño */
        }

        /* Estilos para las celdas de la tabla */
        td {
            padding: 15px; /* Relleno interno */
            color: #2c3e50; /* Color del texto gris oscuro */
            border-bottom: 1px solid #ecf0f1; /* Línea divisoria entre filas */
        }

        /* Elimina el borde de la última fila */
        tr:last-child td {
            border-bottom: none; /* Sin línea divisoria en la última fila */
        }

        /* Efecto hover sobre las filas de la tabla */
        tr:hover {
            background-color: #f8f9fa; /* Fondo gris muy claro al pasar el cursor */
        }

        /* Estilos para la última fila (fila del TOTAL) */
        tr:last-child {
            background-color: #e8f4f8; /* Fondo azul muy claro */
            font-weight: bold; /* Texto en negrita */
        }

        /* Hover sobre la fila del total */
        tr:last-child:hover {
            background-color: #d4ebf2; /* Azul un poco más oscuro al hacer hover */
        }

        /* Estilos para los enlaces de acción dentro de las celdas (Eliminar) */
        td a {
            color: #e74c3c; /* Color rojo para el texto */
            text-decoration: none; /* Sin subrayado */
            font-weight: 600; /* Texto semi-negrita */
            padding: 6px 12px; /* Relleno interno para crear un botón */
            border-radius: 5px; /* Bordes redondeados */
            transition: all 0.3s ease; /* Transición suave */
            display: inline-block; /* Para que padding y bordes funcionen correctamente */
        }

        /* Hover sobre el enlace de eliminar */
        td a:hover {
            background-color: #e74c3c; /* Fondo rojo */
            color: white; /* Texto blanco (invierte los colores) */
        }

        /* Línea separadora horizontal */
        hr {
            border: none; /* Elimina el borde predeterminado */
            height: 2px; /* Altura de la línea */
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.5), transparent); /* Degradado: transparente-blanco-transparente */
            margin: 30px auto; /* Espacio arriba y abajo, centrado */
            max-width: 800px; /* Ancho máximo */
        }

        /* Estilos para el enlace de "Volver" (al final de la página) */
        body > a {
            display: inline-block; /* Para que padding y efectos funcionen correctamente */
            background: white; /* Fondo blanco */
            color: #667eea; /* Texto púrpura */
            padding: 12px 30px; /* Relleno: 12px arriba/abajo, 30px izq/der */
            text-decoration: none; /* Sin subrayado */
            border-radius: 8px; /* Bordes redondeados */
            font-weight: 600; /* Texto semi-negrita */
            margin: 20px auto; /* Espacio arriba, centrado */
            transition: all 0.3s ease; /* Transición suave */
            box-shadow: 0 4px 15px rgba(0,0,0,0.2); /* Sombra para profundidad */
            display: block; /* Ocupa toda la línea */
            text-align: center; /* Centra el texto */
            max-width: 200px; /* Ancho máximo */
        }

        /* Hover sobre el enlace de volver */
        body > a:hover {
            background: #667eea; /* Fondo púrpura */
            color: white; /* Texto blanco (invierte colores) */
            transform: translateY(-2px); /* Eleva el botón 2px */
            box-shadow: 0 6px 20px rgba(0,0,0,0.3); /* Sombra más pronunciada */
        }

        /* DISEÑO RESPONSIVE - Adaptación para dispositivos móviles */
        @media (max-width: 768px) { /* Se aplica cuando el ancho de pantalla es menor a 768px */
            body {
                padding: 10px; /* Reduce el padding en móviles */
            }
            
            h1 {
                font-size: 1.8em; /* Reduce el tamaño del título principal */
                margin-bottom: 20px; /* Reduce el espacio debajo */
            }
            
            form {
                padding: 20px; /* Reduce el padding de los formularios */
            }
            
            table {
                font-size: 0.9em; /* Reduce el tamaño de fuente de la tabla */
            }
            
            th, td {
                padding: 10px 8px; /* Reduce el padding de celdas y encabezados */
            }
        }

        /* Estilos para el texto placeholder (texto de ayuda en inputs) */
        ::placeholder {
            color: #95a5a6; /* Color gris medio para el placeholder */
            font-style: italic; /* Texto en cursiva */
        }

        /* Estilos para elementos deshabilitados */
        input:disabled,
        select:disabled {
            background-color: #ecf0f1; /* Fondo gris claro para indicar que está deshabilitado */
            cursor: not-allowed; /* Cursor que indica "no permitido" */
            opacity: 0.6; /* Reduce la opacidad para dar apariencia deshabilitada */
        }
        </style>
    </head>
    <body>
        <h1>Agregar Venta</h1>
        
        <!-- Mostrar mensajes -->
        <?php if(!empty($mensajeExito)): ?>
            <p id="comentario" class="mensaje-exito"><?php echo $mensajeExito; ?></p>
        <?php endif; ?>
        
        <?php if(!empty($mensajeError)): ?>
            <p id="comentario" class="mensaje-error"><?php echo $mensajeError; ?></p>
        <?php endif; ?>
        
        <?php if(empty($mensajeExito) && empty($mensajeError)): ?>
            <p id="comentario"></p>
        <?php endif; ?>
        
        <!-- Agregar productos al carrito -->
        <form action="" method="post">
            Seleccione un producto:
            <select name="selProd" id="selProd" required>
                <option value="">-- Seleccione --</option>
                <?php
                $resultadoprod = mysqli_query($link,"SELECT id, nombre, precio, cantidad FROM producto WHERE estado = 1 ORDER BY nombre ASC");
                while($prod = mysqli_fetch_array($resultadoprod)) {
                    echo '<option value="' . $prod['id'] . '" data-precio="' . $prod['precio'] . '" data-stock="' . $prod['cantidad'] . '">' 
                         . $prod['nombre'] . ' - $' . $prod['precio'] . ' (Stock: ' . $prod['cantidad'] . ')</option>';
                }
                ?>
            </select> <br>
            
            <input type="number" name="cant" id="cant" placeholder="Cantidad" min="1" step="1" required> <br>
            <input type="submit" name="btnAgregarProd" value="Agregar al Carrito"> 
        </form>
        
        <!-- Mostrar carrito de VENTA -->
        <?php if(!empty($_SESSION['carrito_venta'])): ?>
            <hr>
            <h3>Carrito de Venta</h3>
            <table border="1" cellpadding="10">
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acción</th>
                </tr>
                <?php
                $totalGeneral = 0;
                foreach($_SESSION['carrito_venta'] as $idProd => $item):
                    $totalGeneral += $item['subtotal'];
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                        <td>$<?php echo number_format($item['precio'], 2); ?></td>
                        <td><?php echo $item['cantidad']; ?></td>
                        <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                        <td><a href="?eliminar=<?php echo $idProd; ?>">Eliminar</a></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3"><strong>TOTAL</strong></td>
                    <td colspan="2"><strong>$<?php echo number_format($totalGeneral, 2); ?></strong></td>
                </tr>
            </table>
            <br>
            
            <!-- Finalizar venta -->
            <form action="" method="post">
                Producto entregado<input type="checkbox" name="ent" id="ent"> <br>
                <input type="submit" name="btnFinalizarVenta" value="Finalizar Venta">
            </form>
        <?php endif; ?>
        
        <br><br>
        <a href="../gVentas.php">Volver</a>
    </body>
</html>