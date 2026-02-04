<html>
    <head>
        <meta charset="utf-8">
        <title>Borrar Venta Seleccionada</title>
        <?php
        include '../../../BD/conex.php'
        ?>
        <link rel="stylesheet" href="estiloEliminarV.css">
    </head>
    <body>
        <h1>Confirmar eliminación de la Venta</h1>
         <p id="comentario"></p>
        
        <div class="info-container">
            <?php
            $id = $_GET['id'];
            $id = intval($id);
            $resultado = mysqli_query($link,"SELECT venta.id, producto.nombre, producto.precio, producto_venta.cantidad from venta join producto_venta join producto on venta.id = producto_venta.idVenta and producto.id = producto_venta.idProd WHERE venta.id = $id");
            $verRes = mysqli_fetch_array($resultado);
            $prodVenta = mysqli_query($link,"SELECT producto.nombre,producto.precio, producto_venta.cantidad from producto join producto_venta on producto.id = producto_venta.idProd WHERE idVenta = $id ")
            ?>
            <p class="info-text"><strong>ID de La venta:</strong> <?php echo $verRes[0]; ?></p>
            <?php
            while($prod = mysqli_fetch_array($prodVenta)) { 
            ?>
                <p class="info-text"><strong>Nombre del producto:</strong> <?php echo $prod[0]; ?></p>
                <p class="info-text"><strong>precio:</strong> <?php echo $prod[1]; ?></p>
                <p class="info-text"><strong>cantidad:</strong> <?php echo $prod[2]; ?></p>
                
            <?php
            }
            
            ?>

        </div>
        
        <form action="" method="post">
            <input type="submit" name="btnBorrar" id="btnBorrar" value="Confirmar Eliminación">
        </form>
        
        <a href="modificarVenta.php">Cancelar y Volver</a>
        
        <?php
        if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btnBorrar'])) {
            $desactivar = mysqli_query($link,"UPDATE venta SET estado = 2 WHERE id = $id");
            echo '<script>
                        document.getElementById("comentario").style.color = "green";
                        document.getElementById("comentario").innerHTML = "Venta Eliminada Correctamente";
                    </script>';
            header("Location: modificarVenta.php");
        }
        ?>
    </body>
</html>