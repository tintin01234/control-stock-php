<html>
    <head>
        <meta charset="utf-8">
        <title>Modificar Productos</title>
        <?php
        include '../../../BD/conex.php';
        ?>
        <style>
            /* ========================================
            RESET BÁSICO
            ======================================== */
        /* Elimina márgenes, rellenos y define box-sizing para todos los elementos */
        * {
            margin: 0; /* Quita todos los márgenes predeterminados del navegador */
            padding: 0; /* Quita todos los rellenos predeterminados del navegador */
            box-sizing: border-box; /* Incluye padding y border en el cálculo del ancho/alto total */
        }

        /* ========================================
        ESTILOS DEL BODY
        ======================================== */

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Define la familia de fuentes del sitio */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); /* Gradiente diagonal púrpura de fondo */
            min-height: 100vh; /* Altura mínima del 100% de la ventana del navegador */
            padding: 40px 20px; /* Relleno interno: 40px arriba/abajo, 20px izquierda/derecha */
            display: flex; /* Activa el modelo de caja flexible (flexbox) */
            flex-direction: column; /* Organiza los elementos en columna vertical */
            align-items: center; /* Centra los elementos horizontalmente */
        }

        /* ========================================
        TÍTULO PRINCIPAL (H1)
        ======================================== */

        h1 {
            color: white; /* Color del texto en blanco */
            font-size: 38px; /* Tamaño de fuente de 38px */
            font-weight: 700; /* Grosor de fuente en negrita (700) */
            text-align: center; /* Alinea el texto al centro */
            margin-bottom: 30px; /* Espaciado inferior de 30px */
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); /* Sombra negra semi-transparente para profundidad */
            letter-spacing: 0.5px; /* Espaciado entre letras de 0.5px */
        }

        /* ========================================
        SUBTÍTULO (H3)
        ======================================== */

        h3 {
            color: white; /* Color del texto en blanco */
            font-size: 24px; /* Tamaño de fuente de 24px */
            font-weight: 600; /* Grosor de fuente semi-negrita */
            text-align: center; /* Alinea el texto al centro */
            margin: 30px 0 20px 0; /* Margen: 30px arriba, 0 lados, 20px abajo */
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); /* Sombra suave para legibilidad */
        }

        /* ========================================
        PÁRRAFO DE COMENTARIOS/MENSAJES
        ======================================== */

        #comentario {
            color: white; /* Color del texto en blanco por defecto */
            font-size: 16px; /* Tamaño de fuente de 16px */
            text-align: center; /* Texto centrado */
            margin-bottom: 30px; /* Espaciado inferior de 30px */
            min-height: 24px; /* Altura mínima para reservar espacio incluso vacío */
            font-weight: 600; /* Grosor de fuente semi-negrita */
            padding: 10px 20px; /* Relleno interno: 10px arriba/abajo, 20px izq/der */
            border-radius: 8px; /* Bordes redondeados de 8px */
            transition: all 0.3s ease; /* Transición suave de 0.3s para cambios de estilo */
            max-width: 600px; /* Ancho máximo de 600px */
        }

        /* ========================================
        FORMULARIO DE BÚSQUEDA
        ======================================== */

        /* Contenedor del formulario de búsqueda */
        form {
            background: rgba(255, 255, 255, 0.95); /* Fondo blanco casi opaco */
            backdrop-filter: blur(15px); /* Efecto de desenfoque del fondo (glassmorphism) */
            border-radius: 15px; /* Bordes redondeados de 15px */
            padding: 20px 25px; /* Relleno interno: 20px arriba/abajo, 25px izq/der */
            margin-bottom: 30px; /* Espaciado inferior de 30px */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); /* Sombra suave para dar profundidad */
            border: 1px solid rgba(255, 255, 255, 0.3); /* Borde blanco semi-transparente de 1px */
            max-width: 600px; /* Ancho máximo de 600px */
            width: 100%; /* Ocupa el 100% del ancho disponible hasta el máximo */
            display: flex; /* Activa flexbox para alinear input y botón */
            gap: 10px; /* Espaciado de 10px entre input y botón */
        }

        /* ========================================
        CAMPO DE TEXTO DE BÚSQUEDA
        ======================================== */

        /* Input de texto para buscar */
        input[type="text"] {
            flex: 1; /* Ocupa todo el espacio disponible en el contenedor flex */
            padding: 12px 18px; /* Relleno interno: 12px arriba/abajo, 18px izq/der */
            border: 2px solid #e2e8f0; /* Borde gris claro de 2px */
            border-radius: 10px; /* Bordes redondeados de 10px */
            font-size: 15px; /* Tamaño de fuente de 15px */
            background: #f7fafc; /* Fondo gris muy claro */
            transition: all 0.3s ease; /* Transición suave de 0.3s para todos los cambios */
            color: #2d3748; /* Color del texto gris oscuro */
            font-family: inherit; /* Hereda la fuente del body */
        }

        /* Efecto focus del input de búsqueda */
        input[type="text"]:focus {
            outline: none; /* Quita el borde azul predeterminado del navegador */
            border-color: #667eea; /* Cambia el color del borde a púrpura */
            background: white; /* Cambia el fondo a blanco puro */
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); /* Añade sombra púrpura semi-transparente */
        }

        /* Efecto hover del input de búsqueda */
        input[type="text"]:hover {
            border-color: #cbd5e0; /* Cambia el borde a gris medio */
            background: white; /* Cambia el fondo a blanco */
        }

        /* Estilo del placeholder */
        input[type="text"]::placeholder {
            color: #a0aec0; /* Color gris medio para el texto placeholder */
            font-style: italic; /* Texto en cursiva */
        }

        /* ========================================
        BOTÓN DE BÚSQUEDA
        ======================================== */

        /* Botón submit del formulario de búsqueda */
        input[type="submit"] {
            padding: 12px 25px; /* Relleno interno: 12px arriba/abajo, 25px izq/der */
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); /* Gradiente azul diagonal */
            border: none; /* Sin borde */
            border-radius: 10px; /* Bordes redondeados de 10px */
            color: white; /* Color del texto en blanco */
            font-size: 14px; /* Tamaño de fuente de 14px */
            font-weight: 600; /* Grosor de fuente semi-negrita */
            text-transform: uppercase; /* Convierte el texto a mayúsculas */
            letter-spacing: 0.5px; /* Espaciado entre letras de 0.5px */
            cursor: pointer; /* Cambia el cursor a manita al pasar sobre el botón */
            transition: all 0.3s ease; /* Transición suave de 0.3s */
            white-space: nowrap; /* Evita que el texto se divida en múltiples líneas */
        }

        /* Efecto hover del botón de búsqueda */
        input[type="submit"]:hover {
            background: linear-gradient(135deg, #3182ce 0%, #2c5282 100%); /* Gradiente azul más oscuro */
            transform: translateY(-2px); /* Eleva el botón 2px hacia arriba */
            box-shadow: 0 6px 15px rgba(66, 153, 225, 0.4); /* Añade sombra azul */
        }

        /* Efecto active del botón de búsqueda */
        input[type="submit"]:active {
            transform: translateY(0); /* Restablece la posición original */
        }

        /* ========================================
        TARJETA DE PRODUCTO (CONTENEDOR)
        ======================================== */

        /* Contenedor principal de cada producto */
        .producto-card {
            background: rgba(255, 255, 255, 0.95); /* Fondo blanco casi opaco */
            backdrop-filter: blur(15px); /* Efecto de desenfoque del fondo (glassmorphism) */
            border-radius: 15px; /* Bordes redondeados de 15px */
            padding: 25px 30px; /* Relleno interno: 25px arriba/abajo, 30px izq/der */
            margin-bottom: 20px; /* Espaciado inferior de 20px entre tarjetas */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15); /* Sombra suave para dar profundidad */
            border: 1px solid rgba(255, 255, 255, 0.3); /* Borde blanco semi-transparente de 1px */
            width: 100%; /* Ocupa el 100% del ancho disponible */
            max-width: 600px; /* Ancho máximo de 600px */
            transition: all 0.3s ease; /* Transición suave de 0.3s para efectos hover */
            display: flex; /* Activa flexbox para organizar contenido interno */
            flex-direction: column; /* Organiza elementos internos en columna */
            gap: 10px; /* Espaciado de 10px entre elementos internos */
        }

        /* Efecto hover sobre la tarjeta completa */
        .producto-card:hover {
            transform: translateY(-5px); /* Eleva la tarjeta 5px hacia arriba */
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2); /* Aumenta la sombra al pasar el mouse */
        }

        /* ========================================
        INFORMACIÓN DEL PRODUCTO
        ======================================== */

        /* Estilo para los párrafos de información dentro de la tarjeta */
        .producto-info {
            color: #2d3748; /* Color gris oscuro para el texto */
            font-size: 15px; /* Tamaño de fuente de 15px */
            line-height: 1.6; /* Altura de línea de 1.6 para mejor legibilidad */
            margin: 0; /* Sin margen para controlar espaciado con gap del contenedor */
        }

        /* Estilo para el texto en negrita (strong) dentro de la información */
        .producto-info strong {
            color: #1a202c; /* Color gris muy oscuro para destacar las etiquetas */
            font-weight: 600; /* Grosor de fuente semi-negrita */
            display: inline-block; /* Permite aplicar estilos de bloque inline */
            min-width: 160px; /* Ancho mínimo para alinear visualmente los valores */
        }

        /* ========================================
        BOTÓN ELIMINAR
        ======================================== */

        /* Estilo del botón "Eliminar" dentro de cada tarjeta */
        .btn-eliminar {
            display: inline-block; /* Permite aplicar padding y margin como bloque inline */
            align-self: flex-start; /* Se alinea al inicio (izquierda) del contenedor flex */
            width: auto; /* Ancho automático según contenido */
            margin-top: 8px; /* Espaciado superior de 8px */
            padding: 10px 25px; /* Relleno interno: 10px arriba/abajo, 25px izq/der */
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); /* Gradiente rojo diagonal */
            color: white; /* Color del texto en blanco */
            text-decoration: none; /* Quita el subrayado predeterminado del enlace */
            border-radius: 8px; /* Bordes redondeados de 8px */
            font-size: 14px; /* Tamaño de fuente de 14px */
            font-weight: 600; /* Grosor de fuente semi-negrita */
            text-transform: uppercase; /* Convierte el texto a mayúsculas */
            letter-spacing: 0.8px; /* Espaciado entre letras de 0.8px */
            transition: all 0.3s ease; /* Transición suave de 0.3s para todos los cambios */
            box-shadow: 0 4px 12px rgba(245, 101, 101, 0.3); /* Sombra roja suave */
            position: relative; /* Posicionamiento relativo para pseudo-elementos */
            overflow: hidden; /* Oculta contenido que se salga del botón */
        }

        /* Pseudo-elemento para crear efecto de brillo deslizante */
        .btn-eliminar::before {
            content: ''; /* Crea un pseudo-elemento vacío */
            position: absolute; /* Posicionamiento absoluto dentro del botón */
            top: 0; /* Alineado al borde superior */
            left: -100%; /* Comienza fuera del botón por la izquierda */
            width: 100%; /* Ancho del 100% */
            height: 100%; /* Alto del 100% */
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent); /* Gradiente horizontal de brillo */
            transition: left 0.6s; /* Transición del desplazamiento en 0.6 segundos */
        }

        /* Activa el efecto de brillo al pasar el mouse */
        .btn-eliminar:hover::before {
            left: 100%; /* Mueve el brillo de izquierda a derecha completamente */
        }

        /* Efecto hover del botón "Eliminar" */
        .btn-eliminar:hover {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%); /* Gradiente rojo más oscuro */
            transform: translateY(-2px); /* Eleva el botón 2px hacia arriba */
            box-shadow: 0 6px 18px rgba(245, 101, 101, 0.5); /* Aumenta la sombra roja */
        }

        /* Efecto active del botón "Eliminar" (al hacer clic) */
        .btn-eliminar:active {
            transform: translateY(0); /* Restablece la posición original */
        }

        /* ========================================
        ENLACE "VOLVER"
        ======================================== */

        /* Estilo para el enlace de navegación "Volver" */
        a[href*='gProductos.php'] {
            display: inline-block; /* Permite aplicar padding y margin como bloque inline */
            margin-top: 40px; /* Espaciado superior de 40px */
            padding: 12px 25px; /* Relleno interno: 12px arriba/abajo, 25px izq/der */
            color: white; /* Color del texto en blanco */
            text-decoration: none; /* Quita el subrayado predeterminado del enlace */
            font-size: 16px; /* Tamaño de fuente de 16px */
            font-weight: 600; /* Grosor de fuente semi-negrita */
            background: rgba(255, 255, 255, 0.2); /* Fondo blanco semi-transparente */
            border: 2px solid white; /* Borde blanco de 2px */
            border-radius: 8px; /* Bordes redondeados de 8px */
            transition: all 0.3s ease; /* Transición suave de 0.3s */
            backdrop-filter: blur(10px); /* Efecto de desenfoque del fondo */
            text-transform: uppercase; /* Convierte el texto a mayúsculas */
            letter-spacing: 0.5px; /* Espaciado entre letras de 0.5px */
        }

        /* Efecto hover del enlace "Volver" */
        a[href*='gProductos.php']:hover {
            background: white; /* Cambia el fondo a blanco sólido */
            color: #667eea; /* Cambia el color del texto a púrpura */
            transform: translateY(-2px); /* Eleva el enlace 2px hacia arriba */
            box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3); /* Añade sombra blanca */
        }

        /* Efecto active del enlace "Volver" */
        a[href*='gProductos.php']:active {
            transform: translateY(0); /* Restablece la posición original al hacer clic */
        }
        /* ========================================
        BOTÓN EDITAR
        ======================================== */

        /* Estilo del botón "Editar" dentro de cada tarjeta */
        .btn-editar {
            display: inline-block; /* Permite aplicar padding y margin como bloque inline */
            align-self: flex-start; /* Se alinea al inicio (izquierda) del contenedor flex */
            width: auto; /* Ancho automático según contenido */
            margin-top: 8px; /* Espaciado superior de 8px */
            padding: 10px 25px; /* Relleno interno: 10px arriba/abajo, 25px izq/der */
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); /* Gradiente azul diagonal */
            color: white; /* Color del texto en blanco */
            text-decoration: none; /* Quita el subrayado predeterminado del enlace */
            border-radius: 8px; /* Bordes redondeados de 8px */
            font-size: 14px; /* Tamaño de fuente de 14px */
            font-weight: 600; /* Grosor de fuente semi-negrita */
            text-transform: uppercase; /* Convierte el texto a mayúsculas */
            letter-spacing: 0.8px; /* Espaciado entre letras de 0.8px */
            transition: all 0.3s ease; /* Transición suave de 0.3s para todos los cambios */
            box-shadow: 0 4px 12px rgba(66, 153, 225, 0.3); /* Sombra azul suave */
            position: relative; /* Posicionamiento relativo para pseudo-elementos */
            overflow: hidden; /* Oculta contenido que se salga del botón */
        }

        /* Pseudo-elemento para crear efecto de brillo deslizante */
        .btn-editar::before {
            content: ''; /* Crea un pseudo-elemento vacío */
            position: absolute; /* Posicionamiento absoluto dentro del botón */
            top: 0; /* Alineado al borde superior */
            left: -100%; /* Comienza fuera del botón por la izquierda */
            width: 100%; /* Ancho del 100% */
            height: 100%; /* Alto del 100% */
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent); /* Gradiente horizontal de brillo */
            transition: left 0.6s; /* Transición del desplazamiento en 0.6 segundos */
        }

        /* Activa el efecto de brillo al pasar el mouse */
        .btn-editar:hover::before {
            left: 100%; /* Mueve el brillo de izquierda a derecha completamente */
        }

        /* Efecto hover del botón "Editar" */
        .btn-editar:hover {
            background: linear-gradient(135deg, #3182ce 0%, #2c5282 100%); /* Gradiente azul más oscuro */
            transform: translateY(-2px); /* Eleva el botón 2px hacia arriba */
            box-shadow: 0 6px 18px rgba(66, 153, 225, 0.5); /* Aumenta la sombra azul */
        }

        /* Efecto active del botón "Editar" (al hacer clic) */
        .btn-editar:active {
            transform: translateY(0); /* Restablece la posición original */
        }
        </style>
    </head>
    <body>
        <h1>Modificar Productos</h1>
        <a href="../gProductos.php">Volver</a>
        <p id="comentario"></p>
        <form action="" method="get">
            <input type="text" name="buscar" placeholder="Buscar por nombre..." value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>">
            <input type="submit" value="Buscar">
        </form>
        
        <?php
        
        if(isset($_GET['buscar']) && !empty($_GET['buscar'])) {
            $busqueda = $_GET['buscar'];
            $result = mysqli_query($link,"SELECT producto.id,producto.nombre, proveedor.nombre, precio, cantidad from producto join proveedor on proveedor.id = producto.idP  WHERE producto.estado = 1 and producto.nombre like '%$busqueda%' ORDER BY proveedor.nombre,producto.nombre");
            if(mysqli_num_rows($result) > 0) {
                echo "<h3>Resultados de búsqueda:</h3>";
                while($productos = mysqli_fetch_array($result)) {
                    echo '<div class="producto-card">';
                    echo '<p class="producto-info"><strong>ID del Producto:</strong> ' . $productos[0] . '</p>';
                    echo '<p class="producto-info"><strong>Nombre del Producto:</strong> ' . $productos[1] . '</p>';
                    echo '<p class="producto-info"><strong>Proveedor:</strong> ' . $productos[2] . '</p>';
                    echo '<p class="producto-info"><strong>Precio:</strong> $' . $productos[3] . '</p>';
                    echo '<p class="producto-info"><strong>Cantidad:</strong> ' . $productos[4] . '</p>';
                    echo "<a href='parteDosProd.php?id=" . $productos[0] . "' class='btn-editar'>Editar</a>";
                    echo "<a href='borrarProd.php?id=" . $productos[0] . "' class='btn-eliminar'>Eliminar</a>";
                    echo '</div>';
                }
            }
        } else {
            $result = mysqli_query($link,"SELECT producto.id,producto.nombre, proveedor.nombre, precio, cantidad from producto join proveedor on proveedor.id = producto.idP  WHERE producto.estado = 1  ORDER BY proveedor.nombre,producto.nombre");
            while($productos = mysqli_fetch_array($result)) {
                echo '<div class="producto-card">';
                echo '<p class="producto-info"><strong>ID del Producto:</strong> ' . $productos[0] . '</p>';
                echo '<p class="producto-info"><strong>Nombre del Producto:</strong> ' . $productos[1] . '</p>';
                echo '<p class="producto-info"><strong>Proveedor:</strong> ' . $productos[2] . '</p>';
                echo '<p class="producto-info"><strong>Precio:</strong> $' . $productos[3] . '</p>';
                echo '<p class="producto-info"><strong>Cantidad:</strong> ' . $productos[4] . '</p>';
                echo "<a href='parteDosProd.php?id=" . $productos[0] . "' class='btn-editar'>Editar</a>";
                echo "<a href='borrarProd.php?id=" . $productos[0] . "' class='btn-eliminar'>Eliminar</a>";
                echo '</div>';
            }
        }
        ?>
       
    </body>
</html>