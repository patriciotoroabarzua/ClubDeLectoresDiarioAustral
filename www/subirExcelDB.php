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
if(isset($_SESSION["arregloExcelTipo"])){
	mysql_connect($_HOSTDB,$_USERDB,$_PASSDB) or die ('Ha fallado la conexión: '.mysql_error());
	mysql_select_db($_NAMEDB) or die ('Error al seleccionar la Base de Datos: '.mysql_error());
	if($_SESSION["arregloExcelTipo"]=="empresas"){
		$suma=0;
		for($i=0;$i<count($_SESSION["arregloExcel"]);$i++){

			if($_SESSION["accionExcel"]=="crear"){
				if($_SESSION["arregloExcel"][$i]["nombre"]!=""){
					$nombre=tildesN($_SESSION["arregloExcel"][$i]["nombre"]);
					$direccion=tildesN($_SESSION["arregloExcel"][$i]["direccion"]);
					$ciudad=$_SESSION["arregloExcel"][$i]["ciudad"];
					$ciudadRelacionada=$_SESSION["arregloExcel"][$i]["ciudadRelacionada"];
					$tipo=$_SESSION["arregloExcel"][$i]["tipo"];
					$logo=$_SESSION["arregloExcel"][$i]["logo"];
					$telefono=$_SESSION["arregloExcel"][$i]["telefono"];
					$web=$_SESSION["arregloExcel"][$i]["web"];
					$ayuda=tildesN($_SESSION["arregloExcel"][$i]["ayuda"]);
					$facebook=$_SESSION["arregloExcel"][$i]["facebook"];
					$id="";

					if($_SESSION["arregloExcel"][$i]["latitud"]=="" && $_SESSION["arregloExcel"][$i]["longitud"]==""){
						if($_SESSION["arregloExcel"][$i]["direccion"]!=""){
							$dirText=$_SESSION["arregloExcel"][$i]["direccion"];
							$ciudadText=$_SESSION["arregloExcel"][$i]["ciudad"];
							$arrayPos=getLatLong($dirText.", ".$ciudadText.", Chile");
							$lat=$arrayPos["lat"];
							$long=$arrayPos["long"];
							usleep(10000);
							//echo $dirText.", ".$ciudadText.", Chile".$lat."-".$long."<br>";
						}else{
							$lat="";
							$long="";
						}
						
					}else{
						$lat=$_SESSION["arregloExcel"][$i]["latitud"];
						$long=$_SESSION["arregloExcel"][$i]["longitud"];
					}
					$mensaje=mysql_query("INSERT INTO empresa (nombre, direccion, ciudadRelacionada,ciudad, tipo, telefono, web, latitud, longitud, ayuda, facebook,logo) 
						VALUES ('".$nombre."', '".$direccion."', '".$ciudadRelacionada."', '".$ciudad."', '".$tipo."', '".$telefono."', '".$web."','".$lat."','".$long."','".$ayuda."','".$facebook."','".$logo."');");
					if($mensaje==1){
						$suma++;
					}
					
				}
			}else{

				if($_SESSION["arregloExcel"][$i]["nombre"]!=""){
					$nombre=tildesN($_SESSION["arregloExcel"][$i]["nombre"]);
					//borrarCupon($cuponId,$tipoOpcion);
					$arregloCupones=searchCuponByEmpresaForDelete(getIdEmpresa($nombre));
					for($i=0;$i<count($arregloCupones);$i++){
						borrarCupon($arregloCupones[$i]["id"],$arregloCupones[$i]["tipoOpcion"]);
					}
					usleep(10000);
					$mensaje=mysql_query("DELETE FROM empresa WHERE nombre='".$nombre."';");
					if($mensaje==1){
						$suma++;
					}
				}

			}
		}
		unset($_SESSION["arregloExcel"]);
		unset($_SESSION["arregloExcelTipo"]);
		if(count($_SESSION["arregloExcel"])==($suma+1)){
			$_SESSION["mensajeTipo"]="exito";
			$_SESSION["mensajeSalida"]= "Creado con exito";
		}else{
			$error=mysql_error();
			if($error!=""){
				$_SESSION["mensajeTipo"]="error";
				$_SESSION["mensajeSalida"]= "Error: ".mysql_error();
			}else{
				$_SESSION["mensajeTipo"]="exito";
				$_SESSION["mensajeSalida"]= "Creado con exito";
			}
		}
		if($_SESSION["accionExcel"]=="crear"){
			unset($_SESSION["accionExcel"]);
			header("location: subirImgMultiple.php");
		}else{
			unset($_SESSION["accionExcel"]);
			header("location: gestorCupones.php");
		}
		
	}else if($_SESSION["arregloExcelTipo"]=="usuarios"){
		$suma=0;
		for($i=0;$i<count($_SESSION["arregloExcel"]);$i++){
			if($_SESSION["accionExcel"]=="crear"){
				if($_SESSION["arregloExcel"][$i]["rut"]!=""){
					$rut=$_SESSION["arregloExcel"][$i]["rut"];
					$nombre=tildesN($_SESSION["arregloExcel"][$i]["nombre"]);
					$direccion=tildesN($_SESSION["arregloExcel"][$i]["direccion"]);
					$mail=$_SESSION["arregloExcel"][$i]["mail"];
					$telefono=$_SESSION["arregloExcel"][$i]["fono"];				
					$tipo="usuario";				
					$password=$_SESSION["arregloExcel"][$i]["password"];
					$fechaNac=str_replace(",",'-', $_SESSION["arregloExcel"][$i]["fechaNac"]);
					$ciudad=tildesN($_SESSION["arregloExcel"][$i]["ciudad"]);
					$mensaje=mysql_query("INSERT INTO usuario (rut, nombre, direccion, ciudad, tipo, fono, mail, password, fechaNac) 
						VALUES ('".$rut."','".$nombre."', '".$direccion."', '".$ciudad."', '".$tipo."', '".$telefono."', '".$mail."','".$password."','".$fechaNac."');");

					if($mensaje==1){
						$suma++;
					}
				}
			}else{
				if($_SESSION["arregloExcel"][$i]["rut"]!=""){
					$rut=$_SESSION["arregloExcel"][$i]["rut"];
					$mensaje=mysql_query("DELETE FROM usuario WHERE rut='".$rut."';");
					if($mensaje==1){
						$suma++;
					}
				}
			}
		}

	
		if(count($_SESSION["arregloExcel"])==($suma+1)){
			$_SESSION["mensajeTipo"]="exito";
			$_SESSION["mensajeSalida"]= "Creado con exito";
		}else{
			if($error!=""){
				$_SESSION["mensajeTipo"]="error";
				$_SESSION["mensajeSalida"]= "Error: ".mysql_error();
			}else{
				$_SESSION["mensajeTipo"]="exito";
				$_SESSION["mensajeSalida"]= "Creado con exito";
			}
		}
		unset($_SESSION["arregloExcel"]);
		unset($_SESSION["arregloExcelTipo"]);
		//$_SESSION["respuestaExcelDB"]="subido con Exito";
		header("location: subirExcel.php?page=usuarios");

	}else{
			//header("location: index.php");
		echo "error";
	}
}else{
	//header("location: index.php");
	echo "error";
}




?>