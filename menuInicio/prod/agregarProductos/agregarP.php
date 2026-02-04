<html>
    <head>
        <meta charset="utf-8">
        <title>Agregar Nuevo Producto</title>
        <?php
        include '../../../BD/conex.php'
        ?>
        <link rel="stylesheet" href="estiloAgregarP.css">
    </head>
    <body>
        <h1>Agregar un Producto Nuevo</h1>
        <p id="comentario"></p>
        <?php
        $result = mysqli_query($link,"SELECT * FROM proveedor WHERE estado = 1")
        ?>
        <form action="" method="post">
            <input type="text" name="nombreP" id="nombreP" autofocus placeholder="Nombre del Producto"> <br>
            <select name="nomProv" id="nomProv">
                <option value="">Proveedor</option>
                <?php
                while($prov = mysqli_fetch_array($result)) {
                    echo '<option value="'. $prov['id'] . '">'. $prov['nombre'] .'</option>';
                }
                ?>
            </select> <br>
            <input type="int" name="precio" id="precio" placeholder="Ingrese el precio del Producto por unidad"> <br>
            <input type="submit" name="subirProd" id="subirProd" value="Agregar Producto">
        </form>
        <a href="../gProductos.php">Volver al Menú de opciones</a>
        <?php
        if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['nombreP'])) {
            $nombreP = $_POST['nombreP'];
            $idProv = $_POST['nomProv'];
            $precioP = $_POST['precio'];
            if(!empty($nombreP) && !empty($idProv) && !empty($precioP)) {
                $verifProd = mysqli_query($link,"SELECT * FROM producto WHERE nombre = '$nombreP'");
                if(mysqli_num_rows($verifProd) == 0) {
                    $nuevoProd = mysqli_query($link,"INSERT INTO producto(nombre,idP,precio,estado,cantidad) 
                    VALUES('$nombreP',$idProv,$precioP,1,0)");
                    echo '<script>
                                        document.getElementById("comentario").style.color = "green";
                                        document.getElementById("comentario").innerHTML = "Producto agregado exitosamente";
                            </script';
                } else {
                    echo '<script>
                                        document.getElementById("comentario").style.color = "red";
                                        document.getElementById("comentario").innerHTML = "Producto ya Ingresado";
                            </script';
                }
                
            } else {
                 echo '<script>
                                    document.getElementById("comentario").style.color = "red";
                                    document.getElementById("comentario").innerHTML = "Ingrese los datos para agregar un producto";
                        </script';
            }
        }
        ?>
    </body>
</html>