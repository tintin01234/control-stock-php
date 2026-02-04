<html>
    <head>
        <meta charset="utf-8">
        <title>Borrar Compra Seleccionada</title>
        <?php
        include '../../../BD/conex.php'
        ?>
        <link rel="stylesheet" href="estiloEliminarC.css">
    </head>
    <body>
        <h1>Confirmar eliminación de la Compra</h1>
         <p id="comentario"></p>
        
        <div class="info-container">
            <?php
            $id = $_GET['id'];
            $id = intval($id);
            $resultado = mysqli_query($link,"SELECT compra.id, proveedor.nombre , producto.nombre, producto.precio, producto_compra.cantidad from compra join producto_compra join producto join proveedor on compra.id = producto_compra.idCompra and producto.id = producto_compra.idProd and proveedor.id = producto.idP WHERE compra.id = $id");
            $verRes = mysqli_fetch_array($resultado);
            $prodComp = mysqli_query($link,"SELECT producto.nombre,producto.precio, producto_compra.cantidad from producto join producto_compra on producto.id = producto_compra.idProd WHERE idCompra = $id ")
            ?>
            <p class="info-text"><strong>ID de La compra:</strong> <?php echo $verRes[0]; ?></p>
            <p class="info-text"><strong>Nombre del proveedor:</strong> <?php echo $verRes[1]; ?></p>
            <?php
            while($prod = mysqli_fetch_array($prodComp)) { 
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
        
        <a href="modificarCompra.php">Cancelar y Volver</a>
        
        <?php
        if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btnBorrar'])) {
            $desactivar = mysqli_query($link,"UPDATE compra SET estado = 2 WHERE id = $id");
            echo '<script>
                        document.getElementById("comentario").style.color = "green";
                        document.getElementById("comentario").innerHTML = "Compra Eliminada Correctamente";
                    </script>';
            header("Location: modificarCompra.php");
        }
        ?>
    </body>
</html>