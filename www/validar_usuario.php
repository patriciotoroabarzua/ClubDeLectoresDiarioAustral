<?php
include '../funciones/funciones.php';
  $con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
  $con->query("SET NAMES 'utf8'");
  $usuario = $_POST["admin"];   
  $password = $_POST["password_usuario"];
  $result = mysqli_query($con,"SELECT * FROM usuario WHERE rut = '$usuario'");
  if($row = mysqli_fetch_array($result))
  {
    if($row["password"] == $password)
    {
      session_start(); 
      $_SESSION['aut'] = "si"; 
      $_SESSION['usuario'] = $usuario;
      $_SESSION['rut'] = $row['rut'];
      $_SESSION['nombre'] = $row['nombre'];
      $_SESSION['direccion'] = $row['direccion'];
      $_SESSION['mail'] = $row['mail'];
      $_SESSION['fono'] = $row['fono'];
      $_SESSION['tipo'] = $row['tipo'];
      $_SESSION['fechaNac'] = $row['fechaNac'];
      $_SESSION['id'] = $row['id'];
      $_SESSION['password'] = $row['password'];
      $_SESSION['ciudad'] = $row['ciudad'];

      /*if($_SESSION['tipo']=="admin"){
        header("Location: inicioAdmin.php"); 
      }else {*/
        header("Location: visorPerfilUsuario.php"); 
      //}
    }else{
       header("Location: login.php?erro=contrasena");
    }
  }else{
       header("Location: login.php?erro=usuario");
  }
  //mysqli_free_result($result);
  mysqli_close($con);
?>