<html>
    <head>
        <meta charset="utf-8">
        <title>Modificar Proveedor Seleccionado</title>
        <?php
        include '../../../BD/conex.php';
        ?>
        <link rel="stylesheet" href="estiloParteDos.css">
        <style>
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
            font-weight: 500; /* Grosor de fuente medio */
        }

        /* Efecto focus (cuando el usuario hace clic en el campo) */
        #tel:focus {
            outline: none; /* Quita el borde azul predeterminado del navegador */
            border-color: #667eea; /* Cambia el color del borde a púrpura */
            background: white; /* Cambia el fondo a blanco puro */
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); /* Añade sombra púrpura semi-transparente de 3px */
            transform: translateY(-2px); /* Eleva ligeramente el campo 2px hacia arriba */
        }

        /* Efecto hover (cuando el mouse pasa sobre el campo) */
        #tel:hover {
            border-color: #cbd5e0; /* Cambia el borde a gris medio */
            background: white; /* Cambia el fondo a blanco */
        }
        </style>
    </head>
    <body>
        <h1>
            Modificar datos del proveedor
        </h1>
        <p id="comentario"></p>
        <form action="" method="post">
            <input type="text" name="nombreProv" id="nombreProv"> <br>
            <input type="number" name="tel" id="tel"> <br>
            <input type="submit" name="btnEnv" id="btnEnv">
        </form>
        <a href="modifProv.php">Volver</a>
        <?php
        
        $id = $_GET['id'];
        $id = intval($id);

        $resultado = mysqli_query($link,"SELECT nombre,telefono FROM proveedor WHERE id = '$id'");
        $verRes = mysqli_fetch_assoc($resultado);
        $nomProv = $verRes['nombre'];
        $telProv = $verRes['telefono'];
        echo"<script>
             document.getElementById('nombreProv').value = '$nomProv';
        </script>";
        echo"<script>
             document.getElementById('tel').value = '$telProv';
        </script>";
            
        if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['nombreProv'])) {
            if(!empty($_POST['nombreProv']) && !empty($_POST['tel'])) {
                $nuevoNombre = $_POST['nombreProv'];
                $nuevoTel = $_POST['tel'];
                $verifP = mysqli_query($link, "SELECT * FROM proveedor WHERE (nombre = '$nuevoNombre' OR telefono = '$nuevoTel') AND id != $id");
                if(mysqli_num_rows($verifP) == 0) {
                    $cambiarNom = mysqli_query($link,"UPDATE proveedor SET nombre = '$nuevoNombre', telefono = '$nuevoTel' WHERE id = '$id'");
                    echo '<script>
                            document.getElementById("comentario").style.color = "blue";
                            document.getElementById("comentario").innerHTML = "Proveedor modificado!";
                        </script';
                    header("Location: modifProv.php");
                } else {
                     echo '<script>
                            document.getElementById("comentario").style.color = "red";
                            document.getElementById("comentario").innerHTML = "Datos Repetidos";
                        </script';
                }
                
                
            } else {
                echo '<script>
                            document.getElementById("comentario").style.color = "red";
                            document.getElementById("comentario").innerHTML = "Complete los campos";
                        </script';
             }
        }
        ?>
       
    </body>
</html>