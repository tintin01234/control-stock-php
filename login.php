<?php
    session_start();
    include 'BD/conex.php'
?>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="estiloInicioSesion.css">
    </head>
    <body>
          <h1>
            Software La Excelencia
        </h1>
        <p id="comentario">Bienvenido, ingrese su nombre y contraseña para entrar al sistema</p>
        <div>
            <form action="login.php" method="post">
                <table>
                    <tr>
                        <td>
                            Ingrese el Nombre de Usuario:
                        </td>
                        <td>
                            <input type="text" id="txtNombreUsuario" name= "txtNombreUsuario" autofocus>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Ingrese la Contraseña:
                        </td>

                        <td>
                            <input type="password" id="txtContra" name = "txtContra" >
                        </td>

                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit" value="Confirmar Ingreso" id="boton">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <?php 
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $nom = $_POST["txtNombreUsuario"];
                $contra = $_POST["txtContra"];
                if (!empty($nom) && !empty($contra)){
                    $result = mysqli_query($link,"SELECT * FROM usuario WHERE nombre = '$nom' AND contra = '$contra'");    
                    if (mysqli_num_rows($result) > 0) {
                            $_SESSION['txtNombreUsuario'] = $nom;
                            header("location: menuInicio/menu.php");
                            //ABRIR CAMPO DE INICIO CON LAS OPCIONES Y CREAR TABLAS
                    } else {
                            $nom_valor = htmlspecialchars($nom, ENT_QUOTES, 'UTF-8');
                            echo '<script>
                                    document.getElementById("txtNombreUsuario").value = "' . $nom_valor . '";
                                    document.getElementById("comentario").style.color = "red";
                                    document.getElementById("comentario").innerHTML = "Error a la hora de validar los datos, intente nuevamente";
                                </script>';
                                
                        }

                }else {
                    if (empty($nom)) {
                        echo'<script>
                            document.getElementById("comentario").style.color = "red"
                            document.getElementById("comentario").innerHTML = `Ingrese el nombre de usuario`
                        </script>';
                    } else {
                        if(empty($contra)){
                            $nom_valor = htmlspecialchars($nom, ENT_QUOTES, 'UTF-8');
                            echo'<script>
                                    document.getElementById("txtNombreUsuario").value = "' . $nom_valor . '";
                                    document.getElementById("comentario").style.color = "red";
                                    document.getElementById("comentario").innerHTML = "Ingrese la contraseña";
                                </script>';
                        }
                    }
                }   
                    
                
                
            }
        ?>
        
        
    </body>

</html>