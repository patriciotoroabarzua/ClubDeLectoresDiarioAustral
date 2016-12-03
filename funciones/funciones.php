<?php
include '../funciones/presentacion.php';

$_NAMEDB='diarioAustral';
$_HOSTDB='localhost';
$_USERDB='admin';
$_PASSDB='1234';


$_HOSTDB='localhost';

function getAllCupon($city){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");
	if($city==""){
		$ciudadBuscar="";
	}else{
		$ciudadBuscar="AND empresa.ciudad='".$city."'";
	}
	//$mensaje=mysqli_query($con,"SELECT * FROM cupon");
	$mensaje=mysqli_query($con,"SELECT cupon.id, cupon.titulo, cupon.descripcion, cupon.auxiliar, cupon.lugar, cupon.telefono, cupon.tipoOpcion,empresa.nombre, empresa.logo, empresa.ciudad, empresa.direccion 
		FROM cupon INNER JOIN empresa ON empresa.id = cupon.EmpresaId ".$ciudadBuscar." ORDER BY RAND()");
	while($row = mysqli_fetch_array($mensaje)){
		$array[] = $row;
	}
	if(isset($array)){

		for($i=0;$i<count($array);$i++){

			//m_array($array[$i]);
			$arraySalida[$i]=$array[$i];
			$arraySalida[$i]["auxiliar"]=str_replace(" ", "", $array[$i]["auxiliar"]);
			if($arraySalida[$i]["telefono"]=="NULL"){
				$arraySalida[$i]["telefono"]=="";
			}
			if($arraySalida[$i]["lugar"]=="NULL"){
				$arraySalida[$i]["lugar"]=="";
			}

			$imagenes=mysqli_query($con,"SELECT * FROM imagen WHERE Cuponid='".$array[$i]["id"]."'");
			unset($imagenArrayFinal);
			while($imagenArray = mysqli_fetch_array($imagenes)){
				$imagenArrayFinal[] = $imagenArray;
			}
			if(isset($imagenArrayFinal[0]["imagen"])){
				$arraySalida[$i]["imagen"]=$imagenArrayFinal[0]["imagen"];
			}else{
				$arraySalida[$i]["imagen"]="img/default.jpg";
			}

			if ($array[$i]["nombre"]!="PASSCLUB") {
				# code...
				$logo=$array[$i]["logo"];
				if(isset($logo)){
					if(substr($logo, 0, 3)!="img"){
						$arraySalida[$i]["logo"]="img/".$logo;
					}else{
						$arraySalida[$i]["logo"]=$logo;
					}
				}else{
					$arraySalida[$i]["logo"]="img/default.jpg";
				}
			}else{
				$arraySalida[$i]["logo"]=$arraySalida[$i]["imagen"];
			}
			
			if($array[$i]["tipoOpcion"]=="beneficio"){
				$cupones = mysqli_query($con,"SELECT * FROM beneficio WHERE Cuponid='".$array[$i]["id"]."'");
				$arrayCupones[$i] = mysqli_fetch_array($cupones);
				$arraySalida[$i]["categoria"]=$arrayCupones[$i]["categoria"];
				$arraySalida[$i]["descuento"]=$arrayCupones[$i]["descuento"];

			}else if($array[$i]["tipoOpcion"]=="curso"){
				$cupones = mysqli_query($con,"SELECT * FROM curso WHERE Cuponid='".$array[$i]["id"]."'");
				$arrayCupones[$i] = mysqli_fetch_array($cupones);
				$arraySalida[$i]["fecha"]=$arrayCupones[$i]["fecha"];
				$arraySalida[$i]["precioNormal"]=$arrayCupones[$i]["precioNormal"];
				$arraySalida[$i]["precioSocio"]=$arrayCupones[$i]["precioSocio"];
				$arraySalida[$i]["beneficio"]=$arrayCupones[$i]["beneficio"];
				$arraySalida[$i]["inscripciones"]=$arrayCupones[$i]["inscripciones"];
			}else if($array[$i]["tipoOpcion"]=="promocion"){
				$cupones = mysqli_query($con,"SELECT * FROM promocion WHERE Cuponid='".$array[$i]["id"]."'");
				$arrayCupones[$i] = mysqli_fetch_array($cupones);
				$arraySalida[$i]["vigencia"]=$arrayCupones[$i]["vigencia"];
				$arraySalida[$i]["cuponesDisponibles"]=$arrayCupones[$i]["cuponesDisponibles"];

			}else if($array[$i]["tipoOpcion"]=="social"){
				$cupones = mysqli_query($con,"SELECT * FROM social WHERE Cuponid='".$array[$i]["id"]."'");
				$arrayCupones[$i] = mysqli_fetch_array($cupones);
				$arraySalida[$i]["fecha"]=$arrayCupones[$i]["fecha"];

			}else if($array[$i]["tipoOpcion"]=="panorama"){
				$cupones = mysqli_query($con,"SELECT * FROM panorama WHERE Cuponid='".$array[$i]["id"]."'");
				$arrayCupones[$i] = mysqli_fetch_array($cupones);
				$arraySalida[$i]["fecha"]=$arrayCupones[$i]["fecha"];
				$arraySalida[$i]["beneficio"]=$arrayCupones[$i]["beneficio"];
				$arraySalida[$i]["precioNormal"]=$arrayCupones[$i]["precioNormal"];

			}else if($array[$i]["tipoOpcion"]=="concurso"){
				$cupones = mysqli_query($con,"SELECT * FROM concurso WHERE Cuponid='".$array[$i]["id"]."'");
				$arrayCupones[$i] = mysqli_fetch_array($cupones);
				$arraySalida[$i]["fecha"]=$arrayCupones[$i]["fecha"];
				$arraySalida[$i]["contacto"]=$arrayCupones[$i]["contacto"];
			}
		}
	}
	mysqli_close($con);
	if(isset($arraySalida)){
		return $arraySalida;
	}
}

function getCuponesByType($array,$type,$cantidad){
	$j=0;
	for($i=0;$i<count($array);$i++){
		
		if($array[$i]["tipoOpcion"]==$type){
			if($j<$cantidad){
				$arraySalida[$j]=$array[$i];

				$j++;
			}else{
				break;
			}
		}
		
	}
	if(isset($arraySalida)){
		return $arraySalida;
	}
}
function orderBy($array){
	foreach ($array as $key => $row) {
		$aux[$key] = $row['fecha'];
	}
	array_multisort($aux, SORT_ASC, $array);
	return $array;
}
function getAgenda($array){
	$j=0;
	if(is_array($array)){
		//m_array($array);
		for($i=0;$i<count($array);$i++){
			
			if($array[$i]["tipoOpcion"]=="panorama" || $array[$i]["tipoOpcion"]=="curso"){
				if($j<4){
					$arraySalida[$j]=$array[$i];
					$arraySalida[$j]["fecha"]=fechaLinda($array[$i]["fecha"]);
					
					$j++;
				}else{
					break;
				}
			} 
			
		}
	}
	if(isset($arraySalida)){
		return orderBy($arraySalida);
	}
}
function getCupon($idCupon){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");
	$solicito="cupon.titulo, cupon.descripcion, cupon.lugar,cupon.auxiliar, cupon.telefono, cupon.id, cupon.tipoOpcion, cupon.Empresaid, empresa.nombre, empresa.direccion, empresa.ciudad, empresa.web, empresa.logo, empresa.facebook";
	$mensaje=mysqli_query($con,"SELECT ".$solicito." FROM cupon INNER JOIN empresa ON empresa.id = cupon.EmpresaId AND cupon.id ='".$idCupon."'");
	$row = mysqli_fetch_array($mensaje);
	$array=$row;
	$arraySalida=$array;

	if(isset($arraySalida)){
		$arraySalida["auxiliar"]=str_replace(" ", "", $array["auxiliar"]);
		$logo=$array["logo"];
		if(isset($logo)){
			if(substr($logo, 0, 3)!="img"){
				$arraySalida["logo"]="img/".$logo;
			}else{
				$arraySalida["logo"]=$logo;
			}
		}else{
			$arraySalida["logo"]="img/default.jpg";
		}
		$imagenes=mysqli_query($con,"SELECT * FROM imagen WHERE Cuponid='".$idCupon."'");
		$numRow=mysqli_num_rows($imagenes);
		if($numRow==0){
			$arraySalida["imagen"][0]="img/default.jpg";
		}else if($numRow==1){
			$rowImg = mysqli_fetch_array($imagenes);
			$arrayImg = $rowImg;
			$arraySalida["imagen"][0]=$arrayImg["imagen"];	
		}else if($numRow>1){
			while($rowImg = mysqli_fetch_array($imagenes)){
				$arrayImg[] = $rowImg;
			}
			$j=0;
			for($cont=0;$cont<count($arrayImg);$cont++){
				if(isset($arrayImg[$cont]["imagen"])){
					$arraySalida["imagen"][$j]=$arrayImg[$cont]["imagen"];
				}else{
					$arraySalida["imagen"][$j]="img/default.jpg";
				}
				$j++;
			}
		}
		if($array["tipoOpcion"]=="beneficio"){
			$cupones = mysqli_query($con,"SELECT * FROM beneficio WHERE Cuponid='".$array["id"]."'");
			$arrayCupones = mysqli_fetch_array($cupones);
			$arraySalida["categoria"]=$arrayCupones["categoria"];
			$arraySalida["descuento"]=$arrayCupones["descuento"];

		}else if($array["tipoOpcion"]=="curso"){
			$cupones = mysqli_query($con,"SELECT * FROM curso WHERE Cuponid='".$array["id"]."'");
			$arrayCupones = mysqli_fetch_array($cupones);
			$arraySalida["fecha"]=$arrayCupones["fecha"];
			$arraySalida["precioNormal"]=$arrayCupones["precioNormal"];
			$arraySalida["precioSocio"]=$arrayCupones["precioSocio"];
			$arraySalida["beneficio"]=$arrayCupones["beneficio"];
			$arraySalida["inscripciones"]=$arrayCupones["inscripciones"];

		}else if($array["tipoOpcion"]=="promocion"){
			$cupones = mysqli_query($con,"SELECT * FROM promocion WHERE Cuponid='".$array["id"]."'");
			$arrayCupones = mysqli_fetch_array($cupones);
			$arraySalida["vigencia"]=$arrayCupones["vigencia"];
			$arraySalida["cuponesDisponibles"]=$arrayCupones["cuponesDisponibles"];

		}else if($array["tipoOpcion"]=="social"){
			$cupones = mysqli_query($con,"SELECT * FROM social WHERE Cuponid='".$array["id"]."'");
			$arrayCupones = mysqli_fetch_array($cupones);
			$arraySalida["fecha"]=$arrayCupones["fecha"];

		}else if($array["tipoOpcion"]=="panorama"){
			$cupones = mysqli_query($con,"SELECT * FROM panorama WHERE Cuponid='".$array["id"]."'");
			$arrayCupones = mysqli_fetch_array($cupones);
			$arraySalida["fecha"]=$arrayCupones["fecha"];
			$arraySalida["beneficio"]=$arrayCupones["beneficio"];
			$arraySalida["precioNormal"]=$arrayCupones["precioNormal"];

		}else if($array["tipoOpcion"]=="concurso"){
			$cupones = mysqli_query($con,"SELECT * FROM concurso WHERE Cuponid='".$array["id"]."'");
			$arrayCupones = mysqli_fetch_array($cupones);
			$arraySalida["fecha"]=$arrayCupones["fecha"];
			$arraySalida["contacto"]=$arrayCupones["contacto"];
		}

		mysqli_close($con);
		return $arraySalida;
	}
}

function searchCupon($titulo,$type,$city){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");
	if($type!=""){
		$type="AND cupon.tipoOpcion = '".$type."' ";
	}
	if($city!=""){

		$city="AND empresa.ciudad = '".$city."' ";
	}
	$mensaje=mysqli_query($con,"SELECT cupon.id, cupon.auxiliar, cupon.tipoOpcion, cupon.titulo, empresa.logo, empresa.ciudadRelacionada FROM cupon INNER JOIN empresa WHERE cupon.Empresaid=empresa.id AND cupon.titulo LIKE '%".$titulo."%' ".$type.$city.";");
	$numRow=mysqli_num_rows($mensaje);

	if($numRow==1){
		$row = mysqli_fetch_array($mensaje);
		$array = $row;
		$arraySalida[0]=$array;
		$arraySalida[0]["auxiliar"]=str_replace(" ", "", $array["auxiliar"]);
		if(isset($array["logo"])){
			$logo=$array["logo"];
			if(substr($logo, 0, 3)!="img"){
				$arraySalida[0]["logo"]="img/".$logo;
			}else{
				$arraySalida[0]["logo"]=$logo;
			}
		}else{
			$arraySalida[0]["logo"]="img/default.jpg";
		}
	}else if($numRow>1){
		while($row = mysqli_fetch_array($mensaje)){
			$array[] = $row;
		}
		for($i=0;$i<count($array);$i++){
			$arraySalida[$i]=$array[$i];
			$arraySalida[$i]["auxiliar"]=str_replace(" ", "", $array[$i]["auxiliar"]);
			$logo=$array[$i]["logo"];
			if(isset($logo)){
				if(substr($logo, 0, 3)!="img"){
					$arraySalida[$i]["logo"]="img/".$logo;
				}else{
					$arraySalida[$i]["logo"]=$logo;
				}
			}else{
				$arraySalida[$i]["logo"]="img/default.jpg";
			}
		}
	}
	mysqli_close($con);
	if(isset($arraySalida)){
		return $arraySalida;	
	}
}
function m_array($arreglo) {
	echo "<hr />";
	if (is_array($arreglo)){
		echo "<pre>";
		print_r($arreglo);
		echo "</pre>";
	}
	else {
		echo "NO ES ARREGLO :(";
	}
}
function getLatLong($direccion){
	$direccion = str_replace(" ", "+", $direccion);
	$json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$direccion&sensor=false");
	$resultado = json_decode($json);
	$estado = $resultado->status;
	if ($estado == "OK")
	{
		$long = $resultado->results[0]->geometry->location->lng;
		$pos["estado"]=$estado;
		$pos["lat"]=$resultado->results[0]->geometry->location->lat;
		$pos["long"]= $resultado->results[0]->geometry->location->lng;	
	}
	else{
		$pos["lat"]="";
		$pos["long"]= "";	
	}
	return $pos;
}
function getAllEmpresas($city){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");
	if($city==""){
		$ciudadBuscar="";
		$salida="var zoomMap=7;";
	}else{
		$ciudadBuscar="WHERE ciudadRelacionada='".$city."'";
		$salida="var zoomMap=13;";
	}
	//$mensaje=mysqli_query($con,"SELECT * FROM cupon");
	
	$mensaje=mysqli_query($con,"SELECT * FROM empresa ".$ciudadBuscar."ORDER BY nombre");

	while($row = mysqli_fetch_array($mensaje)){
		$array[] = $row;
	}
	if(isset($array)){
		$salida.='var empresas=new Array();';
		$j=0;
		for($i=0;$i<count($array);$i++){
			//m_array(getLatLong($array[$i]["direccion"]));
			if($array[$i]["latitud"]!=""){
				$salida.=' empresas['.$j.']=new Array();';
				$salida.=' empresas['.$j.']["id"]= '.$array[$i]["id"].';';
				$salida.=' empresas['.$j.']["nombre"]="'.Ntildes($array[$i]["nombre"]).'";';
				$salida.=' empresas['.$j.']["direccion"]="'.Ntildes($array[$i]["direccion"]).'";';
				$salida.=' empresas['.$j.']["web"]="'.$array[$i]["web"].'";';
				$salida.=' empresas['.$j.']["ciudad"]="'.$array[$i]["ciudadRelacionada"].'";';
				$salida.=' empresas['.$j.']["latitud"]='.$array[$i]["latitud"].';';
				$salida.=' empresas['.$j.']["longitud"]='.$array[$i]["longitud"].';';
				$salida.=' empresas['.$j.']["telefono"]="'.$array[$i]["telefono"].'";';
				$salida.=' empresas['.$j.']["tipo"]="'.$array[$i]["tipo"].'";';
				if(isset($array[$i]["logo"])){
					if(substr($array[$i]["logo"], 0, 3)!="img"){
						$salida.='empresas['.$j.']["logo"]= "img/'.$array[$i]["logo"].'";';
					}else{
						$salida.='empresas['.$j.']["logo"]= "'.$array[$i]["logo"].'";';
					}
					
				}else{
					$salida.='empresas['.$j.']["logo"]= "img/default.jpg";';
				}
				$j++;
			}
		}
	}
	mysqli_close($con);
	if(isset($salida)){
		//echo $salida;
		return $salida;
	}
}
function getEmpresa($empresaId){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");
	$salida="var zoomMap=16;";
	//$mensaje=mysqli_query($con,"SELECT * FROM cupon");
	
	$mensaje=mysqli_query($con,"SELECT * FROM empresa WHERE id=".$empresaId);
	$row = mysqli_fetch_array($mensaje);
	$array = $row;
	if(isset($array)){
		$salida.='var empresas=new Array();';
		$salida.=' empresas[0]=new Array();';
		$salida.=' empresas[0]["id"]= '.$array["id"].';';
		$salida.=' empresas[0]["nombre"]="'.$array["nombre"].'";';
		$salida.=' empresas[0]["direccion"]="'.$array["direccion"].'";';
		$salida.=' empresas[0]["web"]="'.$array["web"].'";';
		$salida.=' empresas[0]["latitud"]='.$array["latitud"].';';
		$salida.=' empresas[0]["longitud"]='.$array["longitud"].';';
		$salida.=' empresas[0]["telefono"]="'.$array["telefono"].'";';
		$salida.=' empresas[0]["tipo"]="'.$array["tipo"].'";';
		if(isset($array["logo"])){
			$salida.='empresas[0]["logo"]= "img/'.$array["logo"].'";';
			if(substr($array["logo"], 0, 3)!="img"){
				$salida.='empresas[0]["logo"]= "img/'.$array["logo"].'";';
			}else{
				$salida.='empresas[0]["logo"]= "'.$array["logo"].'";';
			}

		}else{
			$salida.='empresas[0]["logo"]= "img/default.jpg";';
		}

		
	}
	mysqli_close($con);
	if(isset($salida)){
		//echo $salida;
		return $salida;
	}
}
function getIdEmpresa($empresaNombre){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");
	$mensaje=mysqli_query($con,"SELECT * FROM empresa WHERE nombre='".tildesN($empresaNombre)."'");
	$row = mysqli_fetch_array($mensaje);

	mysqli_close($con);
	if(isset($row)){
		return $row[0];
	}else{
		return 0;
	}
}
function getRelacionados($id,$tipo){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");

	$mensaje=mysqli_query($con,"SELECT * FROM cupon INNER JOIN empresa WHERE cupon.Empresaid=empresa.id AND cupon.id!='".$id."' AND cupon.tipoOpcion='".$tipo."' ORDER BY RAND();");
	$numRow=mysqli_num_rows($mensaje);
	if($numRow==1){
		$row = mysqli_fetch_array($mensaje);
		$array = $row;
		$arraySalida[0]=$array;
		$arraySalida[0]["id"]=$array[5];
		$arraySalida[0]["auxiliar"]=str_replace(" ", "", $array["auxiliar"]);
		if(isset($array["logo"])){
			
			if(substr($array["logo"], 0, 3)!="img"){
				$arraySalida[0]["logo"]="img/".$array["logo"];
			}else{
				$arraySalida[0]["logo"]=$array["logo"];
			}
		}else{
			$arraySalida[0]["logo"]="img/default.jpg";
		}
	}else if($numRow>1){
		while($row = mysqli_fetch_array($mensaje)){
			$array[] = $row;
		}
		for($i=0;$i<count($array);$i++){
			if($i<4){
				$arraySalida[$i]=$array[$i];
				$arraySalida[$i]["id"]=$array[$i][5];
				$arraySalida[$i]["auxiliar"]=str_replace(" ", "", $array[$i]["auxiliar"]);
				$logo=$array[$i]["logo"];
				if(isset($logo)){
					if(substr($logo, 0, 3)!="img"){
						$arraySalida[$i]["logo"]="img/".$logo;
					}else{
						$arraySalida[$i]["logo"]=$logo;
					}
				}else{
					$arraySalida[$i]["logo"]="img/default.jpg";
				}
			}
		}
	}
	mysqli_close($con);
	if(isset($arraySalida)){
		//echo $salida;
		return $arraySalida;
	}
}

function getEmpresasOrderByCity(){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");
	$mensaje=mysqli_query($con,"SELECT id, nombre, ciudad FROM empresa");

	while($row = mysqli_fetch_array($mensaje)){
		$array[] = $row;
	}
	if(isset($array)){
		$salida='var empresas=new Array();<br>';
		$conTemuco=-1;
		$conValdivia=-1;
		$conOsorno=-1;
		$conPuertoMontt=-1;
		$strCity=-1;
		for($i=0;$i<count($array);$i++){
			if($array[$i]["ciudad"]=="Temuco"){
				$strCity="Temuco";
				$conTemuco++;
				$j=$conTemuco;
			}else if($array[$i]["ciudad"]=="Valdivia"){
				$strCity="Valdivia";
				$conValdivia++;
				$j=$conValdivia;
			}else if($array[$i]["ciudad"]=="Osorno"){
				$strCity="Osorno";
				$conOsorno++;
				$j=$conOsorno;
			}else if($array[$i]["ciudad"]=="Puerto Montt"){
				$strCity="Puerto Montt";
				$conPuertoMontt++;
				$j=$conPuertoMontt;
			}
			$salida.=' empresas["'.$strCity.'"]['.$j.']=new Array();<br>';
			$salida.=' empresas["'.$strCity.'"]['.$j.']["id"]= '.$array[$i]["id"].';<br>';
			$salida.=' empresas["'.$strCity.'"]['.$j.']["nombre"]="'.$array[$i]["nombre"].'";<br>';

		}
	}
	mysqli_close($con);
	if(isset($salida)){
		//echo $salida;
		return $salida;
	}
}

function getSlide($ciudadSlide){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");
	//$mensaje=mysqli_query($con,"SELECT * FROM slide ORDER BY posicion ASC;");
	$filtro="WHERE slide.zona = '".$ciudadSlide."'";
	$necesita="cupon.id, cupon.titulo, cupon.tipoOpcion, cupon.auxiliar, slide.posicion, empresa.nombre, empresa.direccion, empresa.ciudad";
	$mensaje=mysqli_query($con,"SELECT ".$necesita." FROM cupon INNER JOIN slide ON slide.idCupon = cupon.id INNER JOIN empresa ON empresa.id = cupon.Empresaid ".$filtro." ORDER BY slide.posicion;");

	while($row = mysqli_fetch_array($mensaje)){
		$array[] = $row;
	}
	
	if(isset($array)){
		for($i=0;$i<count($array);$i++){

			$arraySalida[$i]=$array[$i];
			
			$imagenes=mysqli_query($con,"SELECT imagen FROM imagen WHERE Cuponid='".$array[$i]["id"]."'");
			while($imagenArray = mysqli_fetch_array($imagenes)){
				$imagenArrayFinal[] = $imagenArray;
			}
			if($array[$i]["tipoOpcion"]=="beneficio"){
				$cupones = mysqli_query($con,"SELECT * FROM beneficio WHERE Cuponid='".$array[$i]["id"]."'");
				$arrayCupones[$i] = mysqli_fetch_array($cupones);
				$arraySalida[$i]["categoria"]=$arrayCupones[$i]["categoria"];
				$arraySalida[$i]["descuento"]=$arrayCupones[$i]["descuento"];

			}else if($array[$i]["tipoOpcion"]=="curso"){
				$cupones = mysqli_query($con,"SELECT * FROM curso WHERE Cuponid='".$array[$i]["id"]."'");
				$arrayCupones[$i] = mysqli_fetch_array($cupones);
				$arraySalida[$i]["fecha"]=$arrayCupones[$i]["fecha"];
				$arraySalida[$i]["precioNormal"]=$arrayCupones[$i]["precioNormal"];
				$arraySalida[$i]["precioSocio"]=$arrayCupones[$i]["precioSocio"];
				$arraySalida[$i]["beneficio"]=$arrayCupones[$i]["beneficio"];
				$arraySalida[$i]["inscripciones"]=$arrayCupones[$i]["inscripciones"];

			}else if($array[$i]["tipoOpcion"]=="promocion"){
				$cupones = mysqli_query($con,"SELECT * FROM promocion WHERE Cuponid='".$array[$i]["id"]."'");
				$arrayCupones[$i] = mysqli_fetch_array($cupones);
				$arraySalida[$i]["vigencia"]=$arrayCupones[$i]["vigencia"];
				$arraySalida[$i]["cuponesDisponibles"]=$arrayCupones[$i]["cuponesDisponibles"];

			}else if($array[$i]["tipoOpcion"]=="social"){
				$cupones = mysqli_query($con,"SELECT * FROM social WHERE Cuponid='".$array[$i]["id"]."'");
				$arrayCupones[$i] = mysqli_fetch_array($cupones);
				$arraySalida[$i]["fecha"]=$arrayCupones[$i]["fecha"];

			}else if($array[$i]["tipoOpcion"]=="panorama"){
				$cupones = mysqli_query($con,"SELECT * FROM panorama WHERE Cuponid='".$array[$i]["id"]."'");
				$arrayCupones[$i] = mysqli_fetch_array($cupones);
				$arraySalida[$i]["fecha"]=$arrayCupones[$i]["fecha"];
				$arraySalida[$i]["beneficio"]=$arrayCupones[$i]["beneficio"];
				$arraySalida[$i]["precioNormal"]=$arrayCupones[$i]["precioNormal"];

			}else if($array[$i]["tipoOpcion"]=="concurso"){
				$cupones = mysqli_query($con,"SELECT * FROM concurso WHERE Cuponid='".$array[$i]["id"]."'");
				$arrayCupones[$i] = mysqli_fetch_array($cupones);
				$arraySalida[$i]["fecha"]=$arrayCupones[$i]["fecha"];
				$arraySalida[$i]["contacto"]=$arrayCupones[$i]["contacto"];
			}
			if(isset($imagenArrayFinal[0]["imagen"])){
				$arraySalida[$i]["imagen"]=$imagenArrayFinal[0]["imagen"];
			}else{
				$arraySalida[$i]["imagen"]="img/default.jpg";
			}
			unset($imagenArrayFinal);
		}
	}
	//m_array($arraySalida);
	mysqli_close($con);
	if(isset($arraySalida)){
		//echo $salida;
		return $arraySalida;
	}
}
function borrarCupon($cuponId,$tipoOpcion){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$mensaje1=mysqli_query($con,"DELETE FROM imagen WHERE Cuponid='".$cuponId."';") or die ('Ha fallado la conexiónX: '.mysql_error());
	$mensaje2=mysqli_query($con,"DELETE FROM ".$tipoOpcion." WHERE Cuponid='".$cuponId."';") or die ('Ha fallado la conexiónY: '.mysql_error());
	$mensaje3=mysqli_query($con,"DELETE FROM cupon WHERE id='".$cuponId."';") or die ('Ha fallado la conexiónZ: '.mysql_error());
	
	//m_array($arraySalida);
	mysqli_close($con);
	return (int)$mensaje1+(int)$mensaje2+(int)$mensaje3;
}
function actualizarSlide($arraySlide,$zona){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$mensaje1=mysqli_query($con,"DELETE FROM slide WHERE zona='".$zona."';");
	for($i=0;$i<count($arraySlide);$i++){
		$mensaje2=mysqli_query($con,"INSERT INTO slide (idCupon, posicion, zona) VALUES ('".$arraySlide[$i]["idCupon"]."', ".$arraySlide[$i]["posicion"].",'".$zona."');");// or die('No se ha podido crear: '.mysql_error());
	}
	mysqli_close($con);
	
	//return $mensaje1."-".$mensaje2;
	if($mensaje1==1){
		if(isset($mensaje2)){
			if($mensaje2==1){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 1;
		}

	}else{
		return 0;
	}

}
function getSlideDisponible($ciudadSlide){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");

	$slide="WHERE cupon.slide='si'";
	/*if($ciudadSlide!="todas"){
		$slide.="AND cupon.ciudad";
	}
*/
	//$mensaje=mysqli_query($con,"SELECT * FROM cupon");
	$solicito=	"cupon.id, cupon.titulo, cupon.tipoOpcion, cupon.auxiliar";
	$mensaje=mysqli_query($con,"SELECT ".$solicito." FROM cupon ".$slide);
	while($row = mysqli_fetch_array($mensaje)){
		$array[] = $row;
	}

	if(isset($array)){

		for($i=0;$i<count($array);$i++){

			//m_array($array[$i]);
			$arraySalida[$i]=$array[$i];

			$imagenes=mysqli_query($con,"SELECT * FROM imagen WHERE Cuponid='".$array[$i]["id"]."'");
			unset($imagenArrayFinal);
			while($imagenArray = mysqli_fetch_array($imagenes)){
				$imagenArrayFinal[] = $imagenArray;
			}
			if(isset($imagenArrayFinal[0]["imagen"])){
				$arraySalida[$i]["imagen"]=$imagenArrayFinal[0]["imagen"];
			}else{
				$arraySalida[$i]["imagen"]="img/default.jpg";
			}
			
		}
	}
	mysqli_close($con);
	if(isset($arraySalida)){
		return $arraySalida;
	}
}
function getRevista(){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");

	$mensaje=mysqli_query($con,"SELECT * FROM revista ORDER BY id DESC");
	while($row = mysqli_fetch_array($mensaje)){
		$array[] = $row;
	}
	for($i=0;$i<count($array);$i++){
		$codigo=preg_split("/=/", $array[$i]["revista"]);
		$array[$i]["revistaCodigo"]=$codigo[1];
		$array[$i]["ciudad"]=str_replace("%20", " ", $array[$i]["ciudad"]);
	}
	mysqli_close($con);
	if(isset($array)){
		return $array;
	}
}
function getRevistaCiudad($array, $ciudad){
	$p=0;
	if(isset($array)){
		for($i=0;$i<count($array);$i++){
			if($array[$i]["ciudad"]==$ciudad){
				$arraySalida[$p]=$array[$i];
				$p++;
			}
		}
	}
	if(isset($arraySalida)){
		return $arraySalida;
	}
}
function distintoSlide($arrayAll, $arrayActual){
	//$arraySalida = new Array();
	$p=0;
	if(isset($arrayActual)){
		for($i=0;$i<count($arrayAll);$i++){
			$auxiliar=true;
			for($j=0;$j<count($arrayActual);$j++){	
				if($arrayAll[$i]["id"]==$arrayActual[$j]["id"]){
					$auxiliar=false;
					break;
				}

			}
			if($auxiliar){
				$arraySalida[$p]=$arrayAll[$i];
				$p++;
			}
		}
	}
	if(isset($arraySalida)){
		return $arraySalida;
	}else{
		return $arrayAll;
	}
}
function actualizarPublicidad($publicidad,$zona, $espacio, $url){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());

	$mensaje=mysqli_query($con,"SELECT publicidad FROM publicidad WHERE zona='".$zona."' AND espacio='".$espacio."';");
	$mensaje0=mysqli_fetch_row($mensaje);
	if(isset($mensaje0[0])){
		unlink($mensaje0[0]);
	}
	$mensaje1=mysqli_query($con,"DELETE FROM publicidad WHERE zona='".$zona."' AND espacio='".$espacio."';");
	$mensaje2=mysqli_query($con,"INSERT INTO publicidad (espacio, publicidad, zona, url) 
		VALUES (".$espacio.", '".$publicidad."','".$zona."','".$url."');");
	mysqli_close($con);
	return (int)$mensaje1+(int)$mensaje2;
}
function getPublicidad($zona){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");
	$mensaje=mysqli_query($con,"SELECT * FROM publicidad WHERE zona='".$zona."' ORDER BY espacio");
	while($row = mysqli_fetch_array($mensaje)){
		$array[] = $row;
	}
	mysqli_close($con);
	if(isset($array)){
		return $array;
	}
}
function getCantidadHistorial($cupon){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");
	$mensaje=mysqli_query($con,"SELECT count(*) as cantidad FROM historial 
		WHERE accion='usar' AND cupon='".$cupon."' GROUP BY accion");
	$row = mysqli_fetch_array($mensaje);
	$cantidad = $row;
	mysqli_close($con);
	if(isset($cantidad)){
		return (int)$cantidad["cantidad"];
	}else{
		return 0;
	}
}
function getCasinoHistorial($rut){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");
	$mensaje=mysqli_query($con,"SELECT fecha FROM historial 
		WHERE accion='casino' AND usuario='".$rut."' ORDER BY fecha DESC");
	while($row = mysqli_fetch_array($mensaje)){
		$cantidad[] = $row;
	}
	mysqli_close($con);
	if(isset($cantidad)){
		return $cantidad[0]["fecha"];
	}else{
		return 0;
	}
}
function setHistorial($idCupon,$idUsuario,$accion){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");
	$fecha=date("Y-m-d H:i:s");
	
	$mensaje=mysqli_query($con,"INSERT INTO historial (fecha, cupon, usuario, accion) VALUES ('".$fecha."','".$idCupon."','".$idUsuario."','".$accion."')");
	mysqli_close($con);
}
function getCantidadHistorialEdit($idUsuario){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");
	$mensaje=mysqli_query($con,"SELECT count(*) as cantidad FROM historial 
		WHERE accion='editar' AND usuario='".$idUsuario."' GROUP BY accion");
	$row = mysqli_fetch_array($mensaje);
	$cantidad = $row;
	mysqli_close($con);
	if(isset($cantidad)){
		return (int)$cantidad["cantidad"];
	}else{
		return 0;
	}
}
function descargaCasino(){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$mensaje=mysqli_query($con,"SELECT * FROM casino");
	$numRow=mysqli_num_rows($mensaje);
	if($numRow==0){
		return FALSE;
		mysqli_close($con);
	}else{
		$ultimaDescarga=getCasinoHistorial($_SESSION["rut"]);
		$ultimaDescargaFecha = date("d-m-y", strtotime($ultimaDescarga));
		$row = mysqli_fetch_array($mensaje);
		$array = $row;
		$fechaDesde=date("d-m-y", strtotime($array["desde"]));
		mysqli_close($con);
		if($ultimaDescarga==0 || (strtotime($ultimaDescarga) < strtotime($array["desde"]))){
			return TRUE;
		}else{
			return FALSE;
		}
	}
}
function getCodigosCasino(){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$mensaje=mysqli_query($con,"SELECT * FROM casino");
	while($row = mysqli_fetch_array($mensaje)){
		$array[] = $row;
	}
	$cantidad=$array[0]["cantidad"];
	$j=0;
	for($i=0;$i<count($array);$i++){
		if($j<(int)$cantidad){
			$salida[$j]=$array[$i]["codigo"];
			$mensaje1=mysqli_query($con,"DELETE FROM casino WHERE codigo='".$salida[$j]."'");
			$j++;
		}
	}
	mysqli_close($con);
	if(isset($salida)){
		return $salida;
	}	
}

function fechaLinda($date){
	$dia=date("d",strtotime($date));
	$mes=date("m",strtotime($date));
	if($mes=="01"){
		$mes="ENE";
	}else if($mes=="02"){
		$mes="FEB";
	}else if($mes=="03"){
		$mes="MAR";
	}else if($mes=="04"){
		$mes="ABR";
	}else if($mes=="05"){
		$mes="MAY";
	}else if($mes=="06"){
		$mes="JUN";
	}else if($mes=="07"){
		$mes="JUL";
	}else if($mes=="08"){
		$mes="AGO";
	}else if($mes=="09"){
		$mes="SEP";
	}else if($mes=="10"){
		$mes="OCT";
	}else if($mes=="11"){
		$mes="NOV";
	}else if($mes=="12"){
		$mes="DIC";
	}

	return $dia.$mes;
}
function tildesN($string){
	$string=str_replace("á",'&aacute', $string);
	$string=str_replace("é",'&eacute', $string);
	$string=str_replace("í",'&iacute', $string);
	$string=str_replace("ó",'&oacute', $string);
	$string=str_replace("ú",'&uacute', $string);
	$string=str_replace("ñ",'&ntilde', $string);
	$string=str_replace("Á",'&Aacute', $string);
	$string=str_replace("É",'&Eacute', $string);
	$string=str_replace("Í",'&Iacute', $string);
	$string=str_replace("Ó",'&Oacute', $string);
	$string=str_replace("Ú",'&Uacute', $string);
	$string=str_replace("Ñ",'&Ntilde', $string);
	return $string;
}
function Ntildes($string){
	$string=str_replace("&aacute",'á', $string);
	$string=str_replace("&eacute",'é', $string);
	$string=str_replace('&iacute',"í", $string);
	$string=str_replace('&oacute',"ó", $string);
	$string=str_replace('&uacute',"ú", $string);
	$string=str_replace('&ntilde',"ñ", $string);
	$string=str_replace('&Aacute',"Á", $string);
	$string=str_replace('&Eacute',"É", $string);
	$string=str_replace('&Iacute',"Í", $string);
	$string=str_replace('&Oacute',"Ó", $string);
	$string=str_replace('&Uacute',"Ú", $string);
	$string=str_replace('&Ntilde',"Ñ", $string);
	return $string;
}
function alertaMaxima(){
	if(isset($_SESSION["mensajeTipo"])){

		if($_SESSION["mensajeTipo"]=="exito"){
			echo "<div style='padding: 0.5625rem;
			text-align: center;
			color: #FFF;
			background: #67b918;
			font-size: .9rem;
			text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);'>".$_SESSION["mensajeSalida"]."</div>";

		}else{
			echo "<div style='padding: 0.5625rem;
			text-align: center;
			color: #FFF;
			background: #FF3939;
			font-size: .9rem;
			text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);'>".$_SESSION["mensajeSalida"]."</div>";
		}
		unset($_SESSION["mensajeTipo"]);
		unset($_SESSION["mensajeSalida"]);
	}
}
function searchCuponByEmpresaForDelete($idEmpresa){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");

	$mensaje=mysqli_query($con,"SELECT cupon.id, cupon.tipoOpcion FROM cupon WHERE cupon.Empresaid=".$idEmpresa."");
	$numRow=mysqli_num_rows($mensaje);

	if($numRow==1){
		$row = mysqli_fetch_array($mensaje);
		$array = $row;
		$arraySalida[0]=$array;
	}else if($numRow>1){
		while($row = mysqli_fetch_array($mensaje)){
			$array[] = $row;
		}
		for($i=0;$i<count($array);$i++){
			$arraySalida[$i]=$array[$i];
		}
	}
	mysqli_close($con);
	if(isset($arraySalida)){
		return $arraySalida;	
	}
}
function estadisticaPromoPorMes(){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");
	$mensaje=mysqli_query($con,"SELECT historial.fecha, historial.cupon, historial.usuario, usuario.nombre, historial.accion, DATE_FORMAT( historial.fecha,  '%M-%Y' ) AS 'mes', empresa.ciudadRelacionada
		FROM historial
		INNER JOIN cupon
		INNER JOIN empresa
		INNER JOIN usuario
		WHERE historial.cupon = cupon.id
		AND historial.accion =  'usar'
		AND cupon.EmpresaId = empresa.id
		AND historial.usuario = usuario.rut
		ORDER BY historial.fecha DESC");


	while($row = mysqli_fetch_array($mensaje)){
		$array[] = $row;
	}
	//$c=0;
	$p=0;
	//$casinoMes=$array[0]["mes"];
	$promoClubMes=$array[0]["mes"];
	for($i=0;$i<count($array);$i++){

		if($promoClubMes==$array[$i]["mes"]){
			$arraySalida[$array[$i]["mes"]][$p]=$array[$i];
		}else{
			$arraySalida[$array[$i]["mes"]][0]=$array[$i];
			$promoClubMes=$array[$i]["mes"];
			$p=0;
		}
		$p++;
	}

	mysqli_close($con);
	if(isset($arraySalida)){
		return $arraySalida;	
	}
}
function contarArreglo($array){
	$p=0;
	$aux=$array[0]["cupon"];
	$cantidad=0;
	for ($i=0; $i < count($array); $i++) { 
		if($aux==$array[$i]["cupon"]){
			$cantidad++;
		}
	}
	$arraySalida[0]["cupon"]=$aux;
	$arraySalida[0]["cantidad"]=$cantidad;	
	for ($j=1; $j < count($array); $j++) { 
		# code...
		$aux=$array[$j]["cupon"];
		$cantidad=0;
		for ($i=0; $i < count($array); $i++) { 
			if($aux==$array[$i]["cupon"]){
				$cantidad++;
			}
		}
		if(!existente($arraySalida,$aux)){
			$arraySalida[$p]["cupon"]=$aux;
			$arraySalida[$p]["cantidad"]=$cantidad;
			$p++;
		}
	}
	return $arraySalida;
}
function existente($array, $promo){
	if(isset($array)){
		for ($i=0; $i < count($array); $i++) { 
			# code...
			if($promo==$array[$i]["cupon"]){
				return true;
			}
		}
	}
	return false;
}
function estadisticaCasinoPorMes(){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");
	$mensaje=mysqli_query($con,"SELECT historial.fecha, historial.usuario, usuario.nombre, DATE_FORMAT(historial.fecha,'%M-%Y') as 'mes' 
		FROM historial INNER JOIN usuario
		WHERE historial.accion='casino' AND historial.usuario=usuario.rut
		ORDER BY historial.fecha DESC");


	while($row = mysqli_fetch_array($mensaje)){
		$array[] = $row;
	}
	$c=0;
	$casinoMes=$array[0]["mes"];
	for($i=0;$i<count($array);$i++){
		if($casinoMes==$array[$i]["mes"]){
			$arraySalida[$array[$i]["mes"]][$c]=$array[$i];
		}else{
			$arraySalida[$array[$i]["mes"]][0]=$array[$i];
			$casinoMes=$array[$i]["mes"];
			$c=0;
		}
		$c++;
		
		
	}

	mysqli_close($con);
	if(isset($arraySalida)){
		return $arraySalida;	
	}
}
function estadisticaGetMes(){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");
	$mensaje=mysqli_query($con,"SELECT fecha,DATE_FORMAT(fecha,'%M-%Y') as 'mes'
		FROM historial
		WHERE accion='usar' AND usuario!=''
		ORDER BY fecha DESC");


	while($row = mysqli_fetch_array($mensaje)){
		$array[] = $row;
	}
	//$c=0;
	$p=0;
	//$casinoMes=$array[0]["mes"];
	$promoClubMes[$p]["mes"]=$array[0]["mes"];
	$promoClubMes[$p]["mesT"]=traductorMes($array[0]["mes"]);
	for($i=0;$i<count($array);$i++){

		if($promoClubMes[$p]["mes"]!=$array[$i]["mes"]){
			$p++;
			$promoClubMes[$p]["mes"]=$array[$i]["mes"];	
			$promoClubMes[$p]["mesT"]=traductorMes($array[$i]["mes"]);
		}
		
	}

	mysqli_close($con);
	if(isset($promoClubMes)){
		return $promoClubMes;	
	}
}
function traductorMes($string){
	$string=str_replace("January",'Enero', $string);
	$string=str_replace("February",'Febrero', $string);
	$string=str_replace("March",'Marzo', $string);
	$string=str_replace("April",'Abril', $string);
	$string=str_replace("May",'Mayo', $string);
	$string=str_replace("June",'Junio', $string);
	$string=str_replace("July",'Julio', $string);
	$string=str_replace("August",'Agosto', $string);
	$string=str_replace("September",'Septiembre', $string);
	$string=str_replace("October",'Octubre', $string);
	$string=str_replace("November",'Noviembre', $string);
	$string=str_replace("December",'Diciembre', $string);
	return $string;
}
function getCasinoInfo(){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");
	$mensaje=mysqli_query($con,"SELECT *
		FROM casino");


	while($row = mysqli_fetch_array($mensaje)){
		$array[] = $row;
	}
	$arraySalida["desde"]=$array[0]["desde"];
	$arraySalida["hasta"]=$array[0]["hasta"];
	$arraySalida["cantidad"]=$array[0]["cantidad"];
	$arraySalida["existencia"]=count($array);

	mysqli_close($con);
	if(isset($arraySalida)){
		return $arraySalida;	
	}
}
function getNombreUsuario($rut){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");
	$mensaje=mysqli_query($con,"SELECT nombre FROM usuario 
		WHERE rut='".$rut."'");
	$row = mysqli_fetch_array($mensaje);
	mysqli_close($con);
	if(isset($row)){
		return $row["nombre"];
	}else{
		return 0;
	}
}
function getPromoHistorial($rut, $promo){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");
	$mensaje=mysqli_query($con,"SELECT fecha FROM historial 
		WHERE accion='usar' AND usuario='".$rut."' AND cupon='".$promo."'");
	while($row = mysqli_fetch_array($mensaje)){
		$cantidad[] = $row;
	}
	mysqli_close($con);
	if(isset($cantidad)){
		return $cantidad[0]["fecha"];
	}else{
		return 0;
	}
}
function getCantidadDescargaCupon($cupon,$usuario){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");
	$mensaje=mysqli_query($con,"SELECT count(*) as cantidad FROM historial 
		WHERE accion='usar' AND cupon='".$cupon."' AND usuario='".$usuario."' GROUP BY accion");
	$row = mysqli_fetch_array($mensaje);
	$cantidad = $row;
	mysqli_close($con);
	if(isset($cantidad)){
		return (int)$cantidad["cantidad"];
	}else{
		return 0;
	}
}
function estadisticaPerfilPorMes(){
	global $_NAMEDB, $_HOSTDB, $_USERDB, $_PASSDB;
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");
	$mensaje=mysqli_query($con,"SELECT *, DATE_FORMAT(fecha,'%M-%Y') as 'mes' FROM historial 
		INNER JOIN usuario 
		WHERE historial.accion = 'editar' 
		AND historial.usuario = usuario.rut 
		GROUP BY historial.usuario 
		ORDER BY `historial`.`fecha` DESC");


	while($row = mysqli_fetch_array($mensaje)){
		$array[] = $row;
	}
	//$c=0;
	$p=0;
	//$casinoMes=$array[0]["mes"];
	$arrayEditar=$array[0]["mes"];
	for($i=0;$i<count($array);$i++){

		if($arrayEditar==$array[$i]["mes"]){
			$arraySalida[$array[$i]["mes"]][$p]=$array[$i];
		}else{
			$arraySalida[$array[$i]["mes"]][0]=$array[$i];
			$arrayEditar=$array[$i]["mes"];
			$p=0;
		}
		$p++;
	}

	mysqli_close($con);
	if(isset($arraySalida)){
		return $arraySalida;	
	}
}
?>
