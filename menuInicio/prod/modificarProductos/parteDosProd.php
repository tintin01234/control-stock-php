<html>

    <head>
        <meta charset="utf-8">
        <title>Modificar Producto Seleccionado</title>
        <?php
        include '../../../BD/conex.php';
        ?>
        <link rel="stylesheet" href="estiloParteDosProd.css">
    </head>

    <body>
        <h1>
            Modificar datos del proveedor
        </h1>
        <p id="comentario"></p>
        <?php
        $resultP = mysqli_query($link,"SELECT * from proveedor WHERE estado = 1");
        ?>
        <form action="" method="post">
            Nombre del Producto:<input type="text" name="nombreProd" id="nombreProd" autofocus> <br>
            Precio del Producto:<input type="number" name="precioP" id="precioP" min=1> <br>
            Cantidad:<input type="number" name="cant" id="cant" min=0 step=1> <br>
            <input type="submit" name="btnEnv" id="btnEnv" value="Guardar Cambios">
        </form>
        <a href="modifProd.php">Volver</a>
        <?php
        $id = $_GET['id'];
        $id = intval($id);
        $resultado = mysqli_query($link,"SELECT nombre,precio,cantidad FROM producto WHERE id = '$id'");
        $verRes = mysqli_fetch_assoc($resultado);
        $nomProd = $verRes['nombre'];
        $precioProd = $verRes['precio'];
        $cantidad = $verRes['cantidad'];
        echo"<script>
            document.getElementById('nombreProd').value = '$nomProd';
        </script>";
        echo"<script>
            document.getElementById('precioP').value = $precioProd;
        </script>";
        echo"<script>
            document.getElementById('cant').value = $cantidad;
        </script>";
        
        if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['nombreProd'])) {
            if(!empty($_POST['nombreProd']) && !empty($_POST['precioP'])) {
                $nuevoNombre = $_POST['nombreProd'];
                $nuevoprecio = $_POST['precioP'];
                $nuevaCant = $_POST['cant'];
                $verifP = mysqli_query($link, "SELECT * FROM producto WHERE (nombre = '$nuevoNombre') AND id != $id");
                if(mysqli_num_rows($verifP) == 0) { 
                    $cambiarNom = mysqli_query($link,"UPDATE producto SET nombre = '$nuevoNombre',precio = $nuevoprecio, cantidad = $nuevaCant WHERE id = '$id'");
                    echo '<script>
                            document.getElementById("comentario").style.color = "blue";
                            document.getElementById("comentario").innerHTML = "Producto modificado!";
                        </script';
                    header("Location: modifProd.php");
                } else {
                    echo '<script>
                            document.getElementById("comentario").style.color = "red";
                            document.getElementById("comentario").innerHTML = "Datos repetidos!";
                        </script';
                }
                
            } else {
                echo '<script>
                        document.getElementById("comentario").style.color = "red";
                        document.getElementById("comentario").innerHTML = "Ingrese los datos!";
                    </script';
            }
        }
        ?>
    </body>

</html>