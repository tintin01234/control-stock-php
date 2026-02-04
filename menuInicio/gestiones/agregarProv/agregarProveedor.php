<html>
    <head>
        <title>
            Agregar proveedor nuevo
        </title>
        <meta charset="utf-8">
        <?php
        include '../../../BD/conex.php'
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
                display: flex; /* Activa el modelo de caja flexible (flexbox) */
                flex-direction: column; /* Organiza los elementos hijos en columna vertical */
                align-items: center; /* Centra los elementos horizontalmente en el eje X */
                justify-content: center; /* Centra los elementos verticalmente en el eje Y */
                padding: 40px 20px; /* Relleno interno: 40px arriba/abajo, 20px izquierda/derecha */
            }

            /* ========================================
            TÍTULO PRINCIPAL (H1)
            ======================================== */

            h1 {
                color: white; /* Color del texto en blanco */
                font-size: 42px; /* Tamaño de fuente de 42px */
                font-weight: 700; /* Grosor de fuente en negrita (700) */
                text-align: center; /* Alinea el texto al centro */
                margin-bottom: 20px; /* Espaciado inferior de 20px */
                text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); /* Sombra negra semi-transparente para dar profundidad */
                letter-spacing: 0.5px; /* Espaciado entre letras de 0.5px */
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
            }

            /* Estilos cuando el comentario tiene color rojo (mensajes de éxito/error) */
            #comentario[style*="color: red"],
            #comentario[style*="color:red"] {
                background: rgba(72, 187, 120, 0.2); /* Fondo verde semi-transparente */
                border: 2px solid rgba(72, 187, 120, 0.5); /* Borde verde semi-transparente */
                color: #f0fff4 !important; /* Color verde muy claro forzado */
                box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3); /* Sombra verde para destacar */
                animation: slideInDown 0.5s ease-out; /* Animación de entrada desde arriba */
            }

            /* ========================================
            ESTILOS DEL FORMULARIO
            ======================================== */

            form {
                background: rgba(255, 255, 255, 0.95); /* Fondo blanco casi opaco */
                backdrop-filter: blur(15px); /* Efecto de desenfoque del fondo (glassmorphism) */
                border-radius: 20px; /* Bordes muy redondeados de 20px */
                padding: 40px; /* Relleno interno de 40px en todos los lados */
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15); /* Sombra grande para dar profundidad */
                border: 1px solid rgba(255, 255, 255, 0.2); /* Borde blanco semi-transparente */
                min-width: 450px; /* Ancho mínimo de 450px */
                margin-bottom: 30px; /* Espaciado inferior de 30px */
            }

            /* ========================================
            CAMPO DE TEXTO (INPUT)
            ======================================== */

            #txtNomP {
                width: 100%; /* Ocupa el 100% del ancho del formulario */
                padding: 16px 20px; /* Relleno interno: 16px arriba/abajo, 20px izq/der */
                border: 2px solid #e2e8f0; /* Borde gris claro de 2px */
                border-radius: 12px; /* Bordes redondeados de 12px */
                font-size: 16px; /* Tamaño de fuente de 16px */
                background: #f7fafc; /* Fondo gris muy claro */
                transition: all 0.3s ease; /* Transición suave de 0.3s para todos los cambios */
                color: #2d3748; /* Color del texto gris oscuro */
                font-family: inherit; /* Hereda la fuente del formulario/body */
                margin-bottom: 25px; /* Espaciado inferior de 25px */
            }

            /* Efecto focus (cuando el usuario hace clic en el campo) */
            #txtNomP:focus {
                outline: none; /* Quita el borde azul predeterminado del navegador */
                border-color: #667eea; /* Cambia el color del borde a púrpura */
                background: white; /* Cambia el fondo a blanco puro */
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); /* Añade sombra púrpura semi-transparente */
                transform: translateY(-2px); /* Eleva ligeramente el campo 2px hacia arriba */
            }

            /* Efecto hover (cuando el mouse pasa sobre el campo) */
            #txtNomP:hover {
                border-color: #cbd5e0; /* Cambia el borde a gris medio */
                background: white; /* Cambia el fondo a blanco */
            }

            /* Estilo del placeholder (texto de ejemplo dentro del input) */
            #txtNomP::placeholder {
                color: #a0aec0; /* Color gris medio para el texto placeholder */
                font-style: italic; /* Texto en cursiva */
            }

            #tel {
                width: 100%; /* Ocupa el 100% del ancho del formulario */
                padding: 16px 20px; /* Relleno interno: 16px arriba/abajo, 20px izq/der */
                border: 2px solid #e2e8f0; /* Borde gris claro de 2px */
                border-radius: 12px; /* Bordes redondeados de 12px */
                font-size: 16px; /* Tamaño de fuente de 16px */
                background: #f7fafc; /* Fondo gris muy claro */
                transition: all 0.3s ease; /* Transición suave de 0.3s para todos los cambios */
                color: #2d3748; /* Color del texto gris oscuro */
                font-family: inherit; /* Hereda la fuente del formulario/body */
                margin-bottom: 25px; /* Espaciado inferior de 25px */
            }

            /* Efecto focus (cuando el usuario hace clic en el campo) */
            #tel:focus {
                outline: none; /* Quita el borde azul predeterminado del navegador */
                border-color: #667eea; /* Cambia el color del borde a púrpura */
                background: white; /* Cambia el fondo a blanco puro */
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); /* Añade sombra púrpura semi-transparente */
                transform: translateY(-2px); /* Eleva ligeramente el campo 2px hacia arriba */
            }

            /* Efecto hover (cuando el mouse pasa sobre el campo) */
            #tel:hover {
                border-color: #cbd5e0; /* Cambia el borde a gris medio */
                background: white; /* Cambia el fondo a blanco */
            }

            /* Estilo del placeholder (texto de ejemplo dentro del input) */
            #tel::placeholder {
                color: #a0aec0; /* Color gris medio para el texto placeholder */
                font-style: italic; /* Texto en cursiva */
            }

            /* ========================================
            BOTÓN DE SUBMIT
            ======================================== */

            #btnAceptar {
                width: 100%; /* Ocupa el 100% del ancho del formulario */
                padding: 16px 30px; /* Relleno interno: 16px arriba/abajo, 30px izq/der */
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); /* Gradiente púrpura diagonal */
                border: none; /* Sin borde */
                border-radius: 12px; /* Bordes redondeados de 12px */
                color: white; /* Color del texto en blanco */
                font-size: 16px; /* Tamaño de fuente de 16px */
                font-weight: 600; /* Grosor de fuente semi-negrita */
                text-transform: uppercase; /* Convierte el texto a mayúsculas */
                letter-spacing: 1px; /* Espaciado entre letras de 1px */
                cursor: pointer; /* Cambia el cursor a manita al pasar sobre el botón */
                transition: all 0.3s ease; /* Transición suave de 0.3s para todos los cambios */
                position: relative; /* Posicionamiento relativo para el pseudo-elemento */
                overflow: hidden; /* Oculta contenido que se salga del botón */
            }

            /* Pseudo-elemento para crear el efecto de brillo deslizante */
            #btnAceptar::before {
                content: ''; /* Crea un pseudo-elemento vacío */
                position: absolute; /* Posicionamiento absoluto dentro del botón */
                top: 0; /* Alineado al borde superior */
                left: -100%; /* Comienza fuera del botón por la izquierda */
                width: 100%; /* Ancho del 100% */
                height: 100%; /* Alto del 100% */
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent); /* Gradiente horizontal de brillo */
                transition: left 0.5s; /* Transición del desplazamiento en 0.5 segundos */
            }

            /* Activa el efecto de brillo al pasar el mouse */
            #btnAceptar:hover::before {
                left: 100%; /* Mueve el brillo de izquierda a derecha completamente */
            }

            /* Efecto hover del botón */
            #btnAceptar:hover {
                transform: translateY(-3px); /* Eleva el botón 3px hacia arriba */
                box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4); /* Añade sombra púrpura pronunciada */
            }

            /* Efecto active (al hacer clic en el botón) */
            #btnAceptar:active {
                transform: translateY(-1px); /* Reduce la elevación a solo 1px */
            }

            /* ========================================
            ENLACE DE REGRESO
            ======================================== */

            a {
                display: inline-block; /* Permite aplicar padding y margin como bloque */
                margin-top: 20px; /* Espaciado superior de 20px */
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

            /* Efecto hover del enlace */
            a:hover {
                background: white; /* Cambia el fondo a blanco sólido */
                color: #667eea; /* Cambia el color del texto a púrpura */
                transform: translateY(-2px); /* Eleva el enlace 2px hacia arriba */
                box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3); /* Añade sombra blanca */
            }

            /* Efecto active del enlace */
            a:active {
                transform: translateY(0); /* Restablece la posición original al hacer clic */
            }

            /* ========================================
            ANIMACIONES
            ======================================== */

            /* Animación de entrada desde arriba para mensajes */
            @keyframes slideInDown {
                from {
                    opacity: 0; /* Comienza invisible */
                    transform: translateY(-20px); /* Comienza 20px más arriba */
                }
                to {
                    opacity: 1; /* Termina completamente visible */
                    transform: translateY(0); /* Termina en su posición normal */
                }
}
        </style>
    </head>
    <body>
        <h1>
            Agregar Proveedor
        </h1>
        <P id="comentario"></P>
        <form action="" method = "post">
            <input type="text" name="txtNomP" id="txtNomP" autofocus placeholder="Ingrese el Nombre del Proveedor"> <br>
            <input type="number" name="tel" id="tel" placeholder="Ingrese número de Tel del proveedor"> <br>
            <input type="submit" name="btnAceptar" id="btnAceptar">
        </form>
        <a href="../gProv.php">Volver a la pestaña Proveedores</a>
        <?php
        
        if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['txtNomP'])) {
            $nombreP = $_POST['txtNomP'];
            $telefono = $_POST['tel'];
            if(!empty($nombreP)) {
                $verifP = mysqli_query($link,"SELECT * from proveedor where nombre = '$nombreP' or telefono = '$telefono'");
                if(mysqli_num_rows($verifP) == 0) {
                    $result = mysqli_query($link,"INSERT INTO proveedor(nombre,telefono,estado) values('$nombreP',$telefono,1)");
                    echo '<script>
                                        document.getElementById("comentario").style.color = "green";
                                        document.getElementById("comentario").innerHTML = "Proveedor agregado correctamente";
                            </script';
                } else {
                    echo '<script>
                                        document.getElementById("comentario").style.color = "red";
                                        document.getElementById("comentario").innerHTML = "Datos repetidos";
                            </script';
                }
                
            } else {
                 echo '<script>
                                    document.getElementById("comentario").style.color = "red";
                                    document.getElementById("comentario").innerHTML = "Error a la hora de agregar el proveedor";
                        </script';
            }
            
        }
        ?>
        
    </body>
</html>