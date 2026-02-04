<?php
    session_start();
    if(!isset($_SESSION['txtNombreUsuario'])){
        header("location: login.php");
    }
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Menú</title>
        <link rel="stylesheet" href="estiloMenu.css">
    </head>

    <body>
       <form action="gestiones/gProv.php">
            <input type="submit" value="Gestionar Proveedores" id="btnProv"> <br>
       </form>

       <form action="prod/gProductos.php">
            <input type="submit" value="Gestionar Productos" id="btnProd">  <br>
       </form>
       <form action="compras/gCompras.php">
            <input type="submit" name="" id="" value="Gestionar Compras">
       </form>

        <form action="ventas/gVentas.php">
            <input type="submit" value="Gestionar Ventas" id="btnVentas">  <br>
        </form>
        
        <form action="estadisticas/menuEst.php">
            <input type="submit" value="Ver Estadísticas" id="btnEst">  <br>
        </form>   

        <form action="salir.php">
            <input type="submit" value="Salir" id="btnSalir">
        </form>
             
      
    </body>

</html>