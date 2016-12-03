<?php
include '../config/config.php';
if(isset($_SESSION['tipo'])){
	if($_SESSION['tipo']=='usuario'){
		header("location: index.php");
	}
}else{
	header("location: index.php");
}

$block["meses"]=estadisticaGetMes();
if(isset($_GET["fechaBusqueda"])){
	$fechaBuscar=$_GET["fechaBusqueda"];
	
}else{
	$fechaBuscar=$block["meses"][0]["mes"];
}
$auxiliar=false;
for ($i=0; $i < count($block["meses"]) ; $i++) { 
	# code...
	if ($block["meses"][$i]["mes"]==$fechaBuscar) {
		$auxiliar=true;
		break;
	}
}
if($auxiliar){
	$fields["mesBuscado"]=$fechaBuscar;
	
	$fields["tituloMes"]=traductorMes($fechaBuscar);
	$arregloPromo=estadisticaPromoPorMes();
	if($fechaBuscar==$block["meses"][0]["mes"]){
		$arregloCasino=estadisticaCasinoPorMes();
		$infoCasino=getCasinoInfo();
		$fields["casinoDisponibles"]=$infoCasino["existencia"];
		if(isset($arregloCasino[$fechaBuscar])){
			$fields["casinoDescargados"]=count($arregloCasino[$fechaBuscar]) * (int)$infoCasino["cantidad"];
		}else{
			$fields["casinoDescargados"]= 0 ;
		}
		$fields["casinoTotal"]=(int) $fields["casinoDisponibles"] + (int) $fields["casinoDescargados"];
	}else{
		$fields["casinoDisponibles"]="No Info";
		if(isset($arregloCasino[$fechaBuscar])){
			$fields["casinoDescargados"]=count($arregloCasino[$fechaBuscar])."<br>Personas descargaron" ;
		}else{
			$fields["casinoDescargados"]="No Info" ;
		}
		$fields["casinoTotal"]="No Info";
	}
	$t=0;$o=0;$v=0;$p=0;
	$temucoPromo;
	$osornoPromo;
	$valdiviaPromo;
	$puertoPromo;
	if(isset($arregloPromo[$fechaBuscar])){
		for($i=0;$i<count($arregloPromo[$fechaBuscar]);$i++){
			if($arregloPromo[$fechaBuscar][$i]["ciudadRelacionada"]=="Temuco"){
				$temucoPromo[$t]=$arregloPromo[$fechaBuscar][$i];
				$t++;
			}else if($arregloPromo[$fechaBuscar][$i]["ciudadRelacionada"]=="Osorno"){
				$osornoPromo[$o]=$arregloPromo[$fechaBuscar][$i];
				$o++;
			}else if($arregloPromo[$fechaBuscar][$i]["ciudadRelacionada"]=="Valdivia"){
				$valdiviaPromo[$v]=$arregloPromo[$fechaBuscar][$i];
				$v++;
			}else if($arregloPromo[$fechaBuscar][$i]["ciudadRelacionada"]=="Puerto Montt"){
				$puertoPromo[$p]=$arregloPromo[$fechaBuscar][$i];
				$p++;
			}

		}
	}
}
if(isset($temucoPromo)){
	$arregloContadoTemuco=contarArreglo($temucoPromo);

	for ($i=0; $i < count($arregloContadoTemuco); $i++) { 
		$arregloFinalTemuco[$i]=getCupon($arregloContadoTemuco[$i]["cupon"]);
		$arregloFinalTemuco[$i]["cupon"]=$arregloContadoTemuco[$i]["cupon"];
		$arregloFinalTemuco[$i]["cantidad"]=$arregloContadoTemuco[$i]["cantidad"];
		$arregloFinalTemuco[$i]["resta"]=((int)$arregloFinalTemuco[$i]["cuponesDisponibles"]) - getCantidadHistorial($arregloContadoTemuco[$i]["cupon"]);;
		if($arregloFinalTemuco[$i]["resta"]<0){
			$arregloFinalTemuco[$i]["resta"]=0;
		}
	}
	$block["temuco"]=$arregloFinalTemuco;
}else{
	$block["temuco"]="";
}
if(isset($osornoPromo)){
	$arregloContadoOsorno=contarArreglo($osornoPromo);

	for ($i=0; $i < count($arregloContadoOsorno); $i++) { 
		$arregloFinalOsorno[$i]=getCupon($arregloContadoOsorno[$i]["cupon"]);
		$arregloFinalOsorno[$i]["cupon"]=$arregloContadoOsorno[$i]["cupon"];
		$arregloFinalOsorno[$i]["cantidad"]=$arregloContadoOsorno[$i]["cantidad"];
		$arregloFinalOsorno[$i]["resta"]=((int)$arregloFinalOsorno[$i]["cuponesDisponibles"]) - getCantidadHistorial($arregloContadoOsorno[$i]["cupon"]);;
		if($arregloFinalOsorno[$i]["resta"]<0){
			$arregloFinalOsorno[$i]["resta"]=0;
		}
	}
	$block["osorno"]=$arregloFinalOsorno;
}else{
	$block["osorno"]="";
}
if(isset($valdiviaPromo)){
	$arregloContadoValdivia=contarArreglo($valdiviaPromo);

	for ($i=0; $i < count($arregloContadoValdivia); $i++) { 
		$arregloFinalValdivia[$i]=getCupon($arregloContadoValdivia[$i]["cupon"]);
		$arregloFinalValdivia[$i]["cupon"]=$arregloContadoValdivia[$i]["cupon"];
		$arregloFinalValdivia[$i]["cantidad"]=$arregloContadoValdivia[$i]["cantidad"];
		$arregloFinalValdivia[$i]["resta"]=((int)$arregloFinalValdivia[$i]["cuponesDisponibles"]) - getCantidadHistorial($arregloContadoValdivia[$i]["cupon"]);;
		if($arregloFinalValdivia[$i]["resta"]<0){
			$arregloFinalValdivia[$i]["resta"]=0;
		}
	}
	$block["valdivia"]=$arregloFinalValdivia;
}else{
	$block["valdivia"]="";
}
if(isset($puertoPromo)){
	$arregloContadoPuerto=contarArreglo($puertoPromo);

	for ($i=0; $i < count($arregloContadoPuerto); $i++) { 
		$arregloFinalPuerto[$i]=getCupon($arregloContadoPuerto[$i]["cupon"]);
		$arregloFinalPuerto[$i]["cupon"]=$arregloContadoPuerto[$i]["cupon"];
		$arregloFinalPuerto[$i]["cantidad"]=$arregloContadoPuerto[$i]["cantidad"];
		$arregloFinalPuerto[$i]["resta"]=((int)$arregloFinalPuerto[$i]["cuponesDisponibles"]) - getCantidadHistorial($arregloContadoPuerto[$i]["cupon"]);;
		if($arregloFinalPuerto[$i]["resta"]<0){
			$arregloFinalPuerto[$i]["resta"]=0;
		}
	}
	$block["puerto"]=$arregloFinalPuerto;
}else{
	$block["puerto"]="";
}

$urlPagina="../templates/administracion_cuerpo.html";
$fp                        = file_get_contents($urlPagina, "r");
$head                      = preg_replace("#(.*)<head>(.*?)</head>(.*)#is", '$2', $fp);
$fp                        = str_replace($head, "", $fp);

$obj_page                  = new page_class("estadisticas.html", "../templates/");
$obj_page->add_all($fields, $block);
$body = $obj_page->get_output();
$fp  = str_replace("<body>", "<body>".$body, $fp);

$obj_page                  = new page_class("header-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$enc = $obj_page->get_output();
$fp  = str_replace("</head><body>", $enc, $fp);

$obj_page                  = new page_class("footer-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$fin = $obj_page->get_output();
$fp = str_replace("</body>", $fin . '</body>', $fp);
$fp = str_replace("<title>PassClub</title>", "<title>Estadisticas</title>", $fp);
$fields["contenidoPagina"] = $fp;
$obj_page = new page_class("administracion.html", "../templates");
$obj_page->add_all($fields, "");
$obj_page->display_output();
?>