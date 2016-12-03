<?php 
include '../config/config.php';
if(isset($_SESSION['tipo'])){
	if($_SESSION['tipo']=='usuario'){
		header("location: index.php");
	}
}else{
	header("location: index.php");
}
if(isset($_SESSION["arregloExcel"])){
	mysql_connect($_HOSTDB,$_USERDB,$_PASSDB) or die ('Ha fallado la conexión: '.mysql_error());
	mysql_select_db($_NAMEDB) or die ('Error al seleccionar la Base de Datos: '.mysql_error());
	$mensajeSalida="";
	$mensaje0=mysql_query("TRUNCATE TABLE casino");
	$mensaje1=mysql_query('LOAD DATA LOCAL INFILE "casino.csv" 
			REPLACE INTO TABLE casino 
			FIELDS TERMINATED BY "," 
			LINES TERMINATED BY "\\r\\n";');

	if($mensaje1!="1"){
		$_SESSION["mensajeTipo"]="error";
		$_SESSION["mensajeSalida"]= "Error en la carga";
		
	}else{
		$_SESSION["mensajeTipo"]="exito";
		$_SESSION["mensajeSalida"]= "Carga con exito";
		unset($_SESSION["arregloExcel"]);
	}
	
}else{
	$_SESSION["mensajeTipo"]="error";
	$_SESSION["mensajeSalida"]= "Error en la carga";
}

header("location: subirCasino.php");


?>