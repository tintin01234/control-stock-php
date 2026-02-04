<html>
    <head>
        <meta charset="utf-8">
        <title>Borrar Producto Seleccionado</title>
        <?php
        include '../../../BD/conex.php'
        ?>
        <link rel="stylesheet" href="estiloBorrarProd.css">
    </head>
    <body>
        <h1>Confirmar eliminación del Producto</h1>
         <p id="comentario"></p>
        
        <div class="info-container">
            <?php
            $id = $_GET['id'];
            $id = intval($id);
            $resultado = mysqli_query($link,"SELECT producto.id,producto.nombre,proveedor.nombre,precio,cantidad FROM producto join proveedor on producto.idP = proveedor.id WHERE producto.id = '$id'");
            $verRes = mysqli_fetch_array($resultado);
            ?>
            <p class="info-text"><strong>ID del producto:</strong> <?php echo $verRes[0]; ?></p>
            <p class="info-text"><strong>Nombre del producto:</strong> <?php echo $verRes[1]; ?></p>
            <p class="info-text"><strong>Nombre del proveedor:</strong> <?php echo $verRes[2]; ?></p>
            <p class="info-text"><strong>precio:</strong> <?php echo $verRes['precio']; ?></p>
            <p class="info-text"><strong>cantidad:</strong> <?php echo $verRes['cantidad']; ?></p>


        </div>
        
        <form action="" method="post">
            <input type="submit" name="btnBorrar" id="btnBorrar" value="Confirmar Eliminación">
        </form>
        
        <a href="modifProd.php">Cancelar y Volver</a>
        
        <?php
        if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btnBorrar'])) {
            $desactivar = mysqli_query($link,"UPDATE producto SET estado = 2 WHERE id = $id");
            echo '<script>
                        document.getElementById("comentario").style.color = "green";
                        document.getElementById("comentario").innerHTML = "Proveedor Eliminado Correctamente";
                    </script>';
            header("Location: modifProd.php");
        }
        ?>
    </body>
</html>