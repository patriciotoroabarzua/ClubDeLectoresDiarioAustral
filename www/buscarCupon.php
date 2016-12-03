<?php
include '../config/config.php';
$fields["titulo"]="Todos";
if(!isset($_GET["ciudad"])){
	$ciudad="";
	$fields["ciudadTitulo"]="en PassClub";
}else{
	$ciudad=$_GET["ciudad"];
	$fields["ciudadTitulo"]="en ". ucwords($_GET["ciudad"]);
	if($_GET["ciudad"]=="puerto montt"){	
		$textoCiudad='id="menu-puertomontt"';
	}else{
		$textoCiudad='id="menu-'.strtolower($_GET["ciudad"]).'"';
	}
	$textoCiudadReemplazo=$textoCiudad." class='active'";
}
$salidaNav="";
$salidaNavRem="";
if(!isset($_GET["tipoOpcion"])){
	$tipoOpcion="";
	$fields["tipoOpcionTitulo"]="Todos";
	$fields["colorTitulo"]="primary";
}else{
	$tipoOpcion=$_GET["tipoOpcion"];
	if($tipoOpcion=="curso"){
		$fields["tipoOpcionTitulo"]="Cursos & Talleres";
		$fields["colorTitulo"]="cursos";
	}else if($tipoOpcion=="promocion"){
		$fields["tipoOpcionTitulo"]= "PromoClub";
		$fields["colorTitulo"]= "promoclub";
		$fields["textoPromoClub"]='<div class="envelope-subheader-tax">
		<div class="row">
		<div class="small-12 columns">
		<p>Disfruta de estas increíbles y exclusivas promociones descargando tus cupones. 
		Recuerda que primero tienes que Ingresar tus datos. 
		Si ya ingresaste, sólo debes elegir y descargar tu promoción.</p>
		</div>
		</div>
		</div>';

	}else if($tipoOpcion=="social"){
		$fields["tipoOpcionTitulo"]= "Sociales";
		$fields["colorTitulo"]= "sociales";

	}else if($tipoOpcion=="agenda"){
		$fields["tipoOpcionTitulo"]="Agenda";
		$fields["colorTitulo"]="primary";
	}else{
		$fields["tipoOpcionTitulo"]=ucwords($tipoOpcion."s");
		$fields["colorTitulo"]= $tipoOpcion."s";

	}
	$salidaNav='id="nav-'.$fields["colorTitulo"].'"';
	$salidaNavRem=$salidaNav." class='active'";
}
$title=$fields["tipoOpcionTitulo"]." ".$fields["ciudadTitulo"];
if(!isset($_GET["tituloBuscado"])){
	$array=getAllCupon($ciudad);

	if($tipoOpcion!="" && is_array($array)){
		if($tipoOpcion!="agenda"){
			$array=getCuponesByType($array,$tipoOpcion,1000);
		}else{
			$array=getAgenda($array);
			for ($i=0; $i < count($array); $i++) { 
				# code...
				$array[$i]["auxiliar"]=$array[$i]["fecha"];
			}
			
		}
	}
	//m_array($array);
	$fields["cupones"]=ordenarArrayAll($array);
}else{
	$array=searchCupon($_GET["tituloBuscado"],$tipoOpcion,$ciudad);
	$title="Resultado Busqueda";
	$fields["tipoOpcionTitulo"]="Resultados";
	$fields["ciudadTitulo"]="de búsqueda";
	if(is_array($array)){
		$fields["cupones"]=ordenarArrayAll($array);
	}else{
		$fields["cupones"]="No se encuentran";
	}
}
if(isset($_GET["ciudad"])){
	$ciudad=$_GET["ciudad"];
	$empresas=getAllEmpresas($ciudad);
}else{
	$empresas=getAllEmpresas("");
}
if(isset($empresas)){
	$fields["empresas"] = $empresas;
}

$urlPagina="../templates/administracion_cuerpo.html";
$fp                        = file_get_contents($urlPagina, "r");
$head                      = preg_replace("#(.*)<head>(.*?)</head>(.*)#is", '$2', $fp);
$fp                        = str_replace($head, "", $fp);

$obj_page                  = new page_class("buscarCupon.html", "../templates/");
$obj_page->add_all($fields, $blocks);
$index = $obj_page->get_output();
$fp  = str_replace("<body>", "<body>".$index, $fp);


$obj_page                  = new page_class("searchlog.html", "../templates/inc");
$obj_page->add_all($fields, "");
$searchlog = $obj_page->get_output();
$fp  = str_replace("<body>", "<body>".$searchlog, $fp);

$obj_page                  = new page_class("header-index.html", "../templates/inc");
$obj_page->add_all($fields, "");
$header = $obj_page->get_output();
$fp  = str_replace("<body>", $header, $fp);
$obj_page                  = new page_class("cupons-map.html", "../templates/inc");
$obj_page->add_all($fields, "");
$maps = $obj_page->get_output();
$fp  = str_replace("</body>", $maps."</body>", $fp);

$obj_page                  = new page_class("footer-index.html", "../templates/inc");
$obj_page->add_all($fields, "");
$fin = $obj_page->get_output();
$fp = str_replace("</body>", $fin . '</body>', $fp);
$fp = str_replace("<title>PassClub</title>", "<title>".$title."</title>", $fp);
$fp = str_replace($textoCiudad, $textoCiudadReemplazo, $fp);
$fp = str_replace($salidaNav, $salidaNavRem, $fp);
$fieldsHtml["contenidoPagina"] = $fp;
$obj_page = new page_class("administracion.html", "../templates");
$obj_page->add_all($fieldsHtml, "");
$obj_page->display_output();
?>