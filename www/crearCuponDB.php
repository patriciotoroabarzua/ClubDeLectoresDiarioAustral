<?php
include '../config/config.php';
if(isset($_SESSION['tipo'])){
	if($_SESSION['tipo']=='usuario'){
		header("location: index.php");
	}
}else{
	header("location: index.php");
}
alertaMaxima();
if (isset($_POST['subirCuponBtn'])) {
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");
	$titulo=tildesN($_POST["titulo"]);
	$tipo=($_POST["opcion"]=="Curso & Taller")? "curso": strtolower($_POST["opcion"]);
	$tipo=($tipo=="promoción")? "promocion": $tipo;
	$auxiliar=$_POST["auxiliar"];
	$lugar=($_POST["lugar"]=="")? "NULL": tildesN($_POST["lugar"]);
	$descripcion=tildesN($_POST["descripcion"]);
	$telefono=($_POST["telefono"]=="")? "NULL":$_POST["telefono"];
	$empresa=$_POST["empresa"];
	$slide=$_POST["slide"];
	$id=$_SESSION["id"];
	$idCreado=$tipo.time();
	$mensaje=mysqli_query($con,"INSERT INTO cupon (titulo, descripcion, lugar, telefono, id, Usuarioid,tipoOpcion,Empresaid,slide,auxiliar) 
		VALUES ('".$titulo."', '".$descripcion."', '".$lugar."', '".$telefono."','".$idCreado."',".$id.",'".$tipo."','".$empresa."','".$slide."','".$auxiliar."');");
	if($mensaje==1){
		$_SESSION["IDCUPONCREADO"]=$idCreado;
		if($tipo=="beneficio"){
			$aux1=tildesN($_POST["categoria"]);
			$aux2=tildesN($_POST["descuento"]);
			$mensaje=mysqli_query($con,"INSERT INTO beneficio (categoria, descuento, Cuponid) VALUES ('".$aux1."', '".$aux2."', '".$idCreado."');");	
		}else if($tipo=="curso"){
			$aux1=tildesN($_POST["fecha"]);
			$aux2=tildesN($_POST["precioNormal"]);
			$aux3=tildesN($_POST["precioSocio"]);
			$aux4=tildesN($_POST["beneficio"]);
			$aux5=tildesN($_POST["inscripciones"]);
			$mensaje=mysqli_query($con,"INSERT INTO curso (fecha, precioNormal, precioSocio, beneficio, inscripciones, Cuponid) VALUES ('".$aux1."', '".$aux2."','".$aux3."','".$aux4."','".$aux5."', '".$idCreado."');");
		}else if($tipo=="promocion"){
			$aux1=tildesN($_POST["vigencia"]);
			$aux2=tildesN($_POST["cuponesDisponibles"]);
			$mensaje=mysqli_query($con,"INSERT INTO promocion (vigencia, cuponesDisponibles, Cuponid) VALUES ('".$aux1."', '".$aux2."', '".$idCreado."');");
		}else if($tipo=="social"){
			$aux1=$_POST["fecha"];
			$mensaje=mysqli_query($con,"INSERT INTO social (fecha, Cuponid) VALUES ('".$aux1."','".$idCreado."');");
		}else if($tipo=="panorama"){
			$aux1=$_POST["fecha"];
			$aux2=tildesN($_POST["beneficio"]);
			$aux3=tildesN($_POST["precioNormal"]);
			$mensaje=mysqli_query($con,"INSERT INTO panorama (fecha, beneficio, precioNormal, Cuponid) VALUES ('".$aux1."', '".$aux2."', '".$aux3."', '".$idCreado."');");
		}else if($tipo=="concurso"){
			$aux1=tildesN($_POST["contacto"]);
			$aux2=$_POST["fecha"];
			$mensaje=mysqli_query($con,"INSERT INTO concurso (contacto, fecha, Cuponid) VALUES ('".$aux1."', '".$aux2."', '".$idCreado."');");
		}
		if($mensaje==0){
			$mensaje = mysql_error();
		}
	}else{
		$mensaje = mysql_error();
	}
	mysqli_close($con);
	if($mensaje==1){
		$_SESSION["mensajeTipo"]="exito";
		$_SESSION["mensajeSalida"]= "Cupón creado con exito";
		header("Location: subir_img.php");
	}else{

		$_SESSION["mensajeTipo"]="error";
		$_SESSION["mensajeSalida"]= "Error en la creación del cupon";
		header("Location: crearCupon.php");
	}
}
?>