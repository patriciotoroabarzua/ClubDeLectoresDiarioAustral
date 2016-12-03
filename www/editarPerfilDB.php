<?php
include '../config/config.php';
if(isset($_SESSION['tipo'])){

}else{
	header("location: index.php");
}
alertaMaxima();
if($_POST["passwordActual"]==$_SESSION["password"]){
	mysql_connect($_HOSTDB,$_USERDB,$_PASSDB) or die ('Ha fallado la conexión: '.mysql_error());
	mysql_select_db($_NAMEDB) or die ('Error al seleccionar la Base de Datos: '.mysql_error());
	$nombre=tildesN($_POST["nombre"]);
	$rut=$_POST["rut"];
	$direccion=tildesN($_POST["direccion"]);
	$mail=$_POST["mail"];
	$telefono=$_POST["fono"];
	$fechaNac=$_POST["fechaNac"];
	$password=$_POST["passwordNuevo"];
	if($_POST["passwordNuevo"]!="" && $_POST["passwordNuevo"]==$_POST["repetePass"]){
		$mensaje=mysql_query("UPDATE usuario SET nombre='".$nombre."', direccion='".$direccion."', mail='".$mail."', fechaNac='".$fechaNac."',password='".$password."', fono=".$telefono." 	WHERE rut='".$rut."';");
	}else{	
		$mensaje=mysql_query("UPDATE usuario SET nombre='".$nombre."', direccion='".$direccion."', mail='".$mail."', fechaNac='".$fechaNac."', fono=".$telefono." 	WHERE rut='".$rut."';");
	}
	mysql_close();
	if($mensaje==1){
		$_SESSION["mensajeTipo"]="exito";
		$_SESSION["mensajeSalida"]= "Actualizado con exito";
		$_SESSION['nombre'] = $_POST["nombre"];
      	$_SESSION['direccion'] = $_POST["direccion"];
      	$_SESSION['mail'] = $_POST["mail"];
      	$_SESSION['fono'] = $_POST["fono"];
      	$_SESSION['fechaNac'] = $_POST["fechaNac"];
      	if($_POST["passwordNuevo"]!=""){
      		$_SESSION['password'] = $_POST["passwordNuevo"];
      	}
      	header("location: visorPerfilUsuario.php");
	}else{
		$_SESSION["mensajeTipo"]="error";
		$_SESSION["mensajeSalida"]= "No actualizado";
		header("location: editorUsuario.php");
	}
}else{
	$_SESSION["mensajeTipo"]="error";
	$_SESSION["mensajeSalida"]= "Contraseña Incorrecta";
	header("location: editorUsuario.php");
}

?>