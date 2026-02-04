<html>
    <head>
        <title>Borrar Proveedor</title>
        <meta charset="utf-8">
        <?php
        include '../../../BD/conex.php'
        ?>
        <link rel="stylesheet" href="estiloBorrarP.css">
    </head>
    <body>
        <h1>Confirmar Eliminación de Proveedor</h1>
        <p id="comentario"></p>
        
        <div class="info-container">
            <?php
            $id = $_GET['id'];
            $id = intval($id);
            $resultado = mysqli_query($link,"SELECT * FROM proveedor WHERE id = '$id'");
            $verRes = mysqli_fetch_assoc($resultado);
            ?>
            <p class="info-text"><strong>ID del Proveedor:</strong> <?php echo $verRes['id']; ?></p>
            <p class="info-text"><strong>Nombre del Proveedor:</strong> <?php echo $verRes['nombre']; ?></p>
            <p class="info-text"><strong>Teléfono:</strong> <?php echo $verRes['telefono']; ?></p>
        </div>
        
        <form action="" method="post">
            <input type="submit" name="btnBorrar" id="btnBorrar" value="Confirmar Eliminación">
        </form>
        
        <a href="modifProv.php">Cancelar y Volver</a>
        
        <?php
        if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btnBorrar'])) {
            $desactivar = mysqli_query($link,"UPDATE proveedor SET estado = 2 WHERE id = $id");
            echo '<script>
                        document.getElementById("comentario").style.color = "green";
                        document.getElementById("comentario").innerHTML = "Proveedor Eliminado Correctamente";
                    </script>';
            header("Location: modifProv.php");
        }
        ?>
    </body>
</html>