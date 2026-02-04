<?php
include '../../../BD/conex.php';
session_start();

// Inicializar carrito si no existe
if(!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = array();
}

// ⭐ Obtener mensaje de sesión
$mensajeExito = '';
if(isset($_SESSION['mensaje_exito'])) {
    $mensajeExito = $_SESSION['mensaje_exito'];
    unset($_SESSION['mensaje_exito']);
}

// Cambiar proveedor
if(isset($_GET['cambiar_prov'])) {
    $_SESSION['carrito'] = array();
    unset($_SESSION['proveedor_seleccionado']);
    unset($_SESSION['nombre_proveedor']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Guardar proveedor seleccionado
if(isset($_POST['btnSelProv']) && !empty($_POST['selP'])) {
    $_SESSION['proveedor_seleccionado'] = intval($_POST['selP']);
    
    $idProv = $_SESSION['proveedor_seleccionado'];
    $result = mysqli_query($link, "SELECT nombre FROM proveedor WHERE id = $idProv");
    $prov = mysqli_fetch_assoc($result);
    $_SESSION['nombre_proveedor'] = $prov['nombre'];
    
    // ⭐ Redireccionar
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// ⭐ Agregar producto al carrito (CON REDIRECCIÓN)
if(isset($_POST['btnAgregarProd'])) {
    $idProd = intval($_POST['selProd']);
    $cantidad = intval($_POST['cant']);
    
    if($cantidad > 0 && $idProd > 0) {
        $consultaP = mysqli_query($link, "SELECT nombre, precio FROM producto WHERE id = $idProd");
        $producto = mysqli_fetch_assoc($consultaP);
        
        if($producto) {
            $subtotal = $cantidad * $producto['precio'];
            
            if(isset($_SESSION['carrito'][$idProd])) {
                $_SESSION['carrito'][$idProd]['cantidad'] += $cantidad;
                $_SESSION['carrito'][$idProd]['subtotal'] = $_SESSION['carrito'][$idProd]['cantidad'] * $producto['precio'];
                $_SESSION['mensaje_exito'] = "✓ Cantidad actualizada: " . $producto['nombre'];
            } else {
                $_SESSION['carrito'][$idProd] = array(
                    'nombre' => $producto['nombre'],
                    'precio' => $producto['precio'],
                    'cantidad' => $cantidad,
                    'subtotal' => $subtotal
                );
                $_SESSION['mensaje_exito'] = "✓ Producto agregado: " . $producto['nombre'];
            }
        }
    }
    
    // ⭐ Redireccionar para limpiar POST
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// ⭐ Eliminar producto del carrito (CON REDIRECCIÓN)
if(isset($_GET['eliminar'])) {
    $idEliminar = intval($_GET['eliminar']);
    
    if(isset($_SESSION['carrito'][$idEliminar])) {
        $nombreEliminado = $_SESSION['carrito'][$idEliminar]['nombre'];
        unset($_SESSION['carrito'][$idEliminar]);
        $_SESSION['mensaje_exito'] = "✓ Producto eliminado: " . $nombreEliminado;
    }
    
    // ⭐ Redireccionar para limpiar GET
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Finalizar compra
if(isset($_POST['btnFinalizarCompra']) && !empty($_SESSION['carrito'])) {
    $crearCompra = mysqli_query($link,"INSERT INTO compra(fecha_compra, estado) VALUES(NOW(), 3)");
    $idCompra = mysqli_insert_id($link);
    
    foreach($_SESSION['carrito'] as $idProd => $item) {
        $cantidad = $item['cantidad'];
        $total = $item['subtotal'];
        
        $pedidoCompra = mysqli_query($link,
            "INSERT INTO producto_compra(idProd, idCompra, cantidad, total) 
             VALUES($idProd, $idCompra, $cantidad, $total)");
    }
    
    $_SESSION['carrito'] = array();
    unset($_SESSION['proveedor_seleccionado']);
    unset($_SESSION['nombre_proveedor']);
    
    header("Location: ../gCompras.php?mensaje=Compra registrada exitosamente");
    exit;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Nueva Compra</title>
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
            color: #2c3e50; /* Color gris oscuro para el texto */
            margin-bottom: 20px; /* Espacio debajo del subtítulo */
            font-size: 1.3em; /* Tamaño de fuente mediano */
        }

        /* Estilos para el mensaje de comentario/feedback */
        #comentario {
            text-align: center; /* Centra el texto */
            font-size: 1.1em; /* Tamaño ligeramente más grande */
            font-weight: bold; /* Texto en negrita */
            margin-bottom: 20px; /* Espacio debajo */
            min-height: 25px; /* Altura mínima para evitar saltos visuales */
            animation: fadeIn 0.5s ease; /* Aplica animación de aparición */
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

        /* Estilo específico para el botón de finalizar compra (verde) */
        input[name="btnFinalizarCompra"] {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); /* Degradado verde */
            margin-top: 20px; /* Espacio adicional arriba para separarlo */
            font-size: 1.2em; /* Tamaño más grande para destacar */
            padding: 16px; /* Más relleno que los otros botones */
        }

        /* Hover del botón de finalizar compra */
        input[name="btnFinalizarCompra"]:hover {
            box-shadow: 0 6px 20px rgba(17, 153, 142, 0.4); /* Sombra verde difuminada */
        }

        /* Re-estilización de h3 para la sección del proveedor seleccionado */
        h3 {
            background: white; /* Fondo blanco */
            padding: 20px; /* Relleno interior */
            border-radius: 12px; /* Bordes redondeados */
            box-shadow: 0 4px 15px rgba(0,0,0,0.2); /* Sombra para elevación */
            margin-bottom: 25px; /* Espacio debajo */
            display: flex; /* Usa flexbox para alinear elementos */
            align-items: center; /* Centra verticalmente el contenido */
            justify-content: space-between; /* Distribuye espacio entre nombre y botón */
        }

        /* Estilos para el enlace "Cambiar" dentro de h3 */
        h3 a {
            background: #e74c3c; /* Fondo rojo */
            color: white; /* Texto blanco */
            padding: 8px 15px; /* Relleno interno */
            border-radius: 6px; /* Bordes redondeados */
            text-decoration: none; /* Quita el subrayado del enlace */
            font-size: 0.85em; /* Tamaño de fuente más pequeño */
            transition: all 0.3s ease; /* Transición suave */
            font-weight: normal; /* Peso de fuente normal (no heredar bold de h3) */
        }

        /* Hover sobre el enlace de cambiar proveedor */
        h3 a:hover {
            background: #c0392b; /* Rojo más oscuro al hacer hover */
            transform: scale(1.05); /* Aumenta el tamaño un 5% */
        }

        /* Estilos para la tabla del carrito de compras */
        table {
            width: 100%; /* Ocupa todo el ancho disponible */
            background: white; /* Fondo blanco */
            border-radius: 12px; /* Bordes redondeados */
            overflow: hidden; /* Oculta contenido que sobresale (importante para border-radius) */
            box-shadow: 0 8px 30px rgba(0,0,0,0.3); /* Sombra para profundidad */
            border-collapse: collapse; /* Elimina espacios entre celdas */
            margin-bottom: 20px; /* Espacio debajo de la tabla */
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
            background: linear-gradient(to right, transparent, white, transparent); /* Degradado: transparente-blanco-transparente */
            margin: 30px 0; /* Espacio arriba y abajo de 30px */
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
            margin-top: 20px; /* Espacio arriba */
            transition: all 0.3s ease; /* Transición suave */
            box-shadow: 0 4px 15px rgba(0,0,0,0.2); /* Sombra para profundidad */
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
            
            h3 {
                flex-direction: column; /* Cambia de horizontal a vertical (apila elementos) */
                gap: 10px; /* Espacio de 10px entre el texto y el botón */
                text-align: center; /* Centra el texto */
            }
        }

        /* Animación de pulsación (para estados de carga) */
        @keyframes pulse {
            0%, 100% { /* Al inicio (0%) y al final (100%) */
                opacity: 1; /* Completamente visible */
            }
            50% { /* A la mitad de la animación (50%) */
                opacity: 0.5; /* Semi-transparente */
            }
        }

        /* Clase para aplicar animación de carga a formularios */
        form.loading {
            animation: pulse 1.5s ease-in-out infinite; /* Aplica animación pulse, duración 1.5s, repetición infinita */
        }

        /* Estilos para las opciones dentro de los select */
        option {
            padding: 10px; /* Relleno interno para cada opción */
        }

        /* Estilo para la opción seleccionada actualmente */
        option:checked {
            background: #667eea; /* Fondo púrpura */
            color: white; /* Texto blanco */
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
        <h1>Agregar Compra</h1>
        <a href="../gCompras.php">Volver</a>
        <p id="comentario" style="color: green;">
            <?php echo $mensajeExito; ?>
        </p>
        
        <!-- Seleccionar proveedor (solo una vez) -->
        <?php if(!isset($_SESSION['proveedor_seleccionado'])): ?>
            <form action="" method="post">
                Seleccione un proveedor:
                <select name="selP" id="selP" required>
                    <option value="">-- Seleccione --</option>
                    <?php
                    $resultadoprov = mysqli_query($link,"SELECT * FROM proveedor WHERE estado = 1");
                    while($prov = mysqli_fetch_array($resultadoprov)) {
                        echo '<option value="' . $prov['id'] . '">' . $prov['nombre'] . '</option>';
                    }
                    ?>
                </select>
                <input type="submit" name="btnSelProv" value="Seleccionar Proveedor">
            </form>
        <?php endif; ?>
        
        <!-- Agregar productos al carrito -->
        <?php if(isset($_SESSION['proveedor_seleccionado'])): ?>
            <h3>
                Proveedor: <?php echo $_SESSION['nombre_proveedor']; ?> 
                <a href="?cambiar_prov=1">(Cambiar)</a>
            </h3>
            
            <form action="" method="post">
                Seleccione un producto:
                <select name="selProd" id="selProd" required>
                    <option value="">-- Seleccione --</option>
                    <?php
                    $idProveedor = $_SESSION['proveedor_seleccionado'];
                    $resultadoprod = mysqli_query($link,"SELECT id, nombre, precio FROM producto WHERE estado = 1 AND idP = $idProveedor");
                    while($prod = mysqli_fetch_array($resultadoprod)) {
                        echo '<option value="' . $prod['id'] . '" data-precio="' . $prod['precio'] . '">' 
                             . $prod['nombre'] . ' - $' . $prod['precio'] . '</option>';
                    }
                    ?>
                </select> <br>
                
                <input type="number" name="cant" id="cant" placeholder="Cantidad" min="1" step="1" required> <br>
                <input type="submit" name="btnAgregarProd" value="Agregar al Carrito">
            </form>
            
            <!-- Mostrar carrito -->
            <?php if(!empty($_SESSION['carrito'])): ?>
                <hr>
                <h3>Carrito de Compra</h3>
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
                    foreach($_SESSION['carrito'] as $idProd => $item):
                        $totalGeneral += $item['subtotal'];
                    ?>
                        <tr>
                            <td><?php echo $item['nombre']; ?></td>
                            <td>$<?php echo $item['precio']; ?></td>
                            <td><?php echo $item['cantidad']; ?></td>
                            <td>$<?php echo $item['subtotal']; ?></td>
                            <td><a href="?eliminar=<?php echo $idProd; ?>">Eliminar</a></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3"><strong>TOTAL</strong></td>
                        <td colspan="2"><strong>$<?php echo $totalGeneral; ?></strong></td>
                    </tr>
                </table>
                <br>
                
                <!-- Finalizar compra -->
                <form action="" method="post">
                    <input type="submit" name="btnFinalizarCompra" value="Finalizar Compra">
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </body>
</html>