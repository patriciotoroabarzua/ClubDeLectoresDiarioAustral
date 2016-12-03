<?php
include '../config/config.php';
if(isset($_SESSION['tipo'])){
	if($_SESSION['tipo']=='usuario'){
		header("location: index.php");
	}
}else{
	header("location: index.php");
}
	$valor=borrarCupon($_GET['cuponid'],$_GET['tipoOpcion']);
	
	if((int)$valor >0){
		$_SESSION["mensajeTipo"]="exito";
		$_SESSION["mensajeSalida"]= "Borrado con exito";
		
	}else{
		$_SESSION["mensajeTipo"]="error";
		$_SESSION["mensajeSalida"]= "No borrado";
	}
	header("location: gestorCupones.php");
?>