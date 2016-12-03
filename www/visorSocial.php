<?php
include '../config/config.php';
if(!isset($_GET["cuponid"])){
	header("Location: index.php");
}
alertaMaxima();
$array=getCupon($_GET["cuponid"]);
if(isset($array)){
	$fields["id"]=$array["id"];
	$fields["titulo"]=Ntildes($array["titulo"]);
	$fields["descripcion"]=Ntildes($array["descripcion"]);
	$fields["lugar"]=$array["lugar"];
	$fields["telefono"]=$array["telefono"];
	$fields["tipoOpcion"]=$array["tipoOpcion"];
	$fields["fecha"]=$array["fecha"];
	$fields["logo"]=$array["logo"];
	$fields["ciudad"]=ucwords($array["ciudad"]);
	$fields["direccion"]=$array["direccion"];
	$fields["imagen"]=$array["imagen"][0];
	$fields["imagenSlide"]=sliderSocial($array["imagen"]);
	$blocks["relacionados"]=getRelacionados($array["id"],$array["tipoOpcion"]);
	if($fields["tipoOpcion"]!="social"){
		header("Location: visor.php?cuponid=".$_GET["cuponid"]);
	}
	$empresas=getEmpresa($array["Empresaid"]);
	if(isset($_SESSION['tipo'])){
		if($_SESSION['tipo']=="admin"){
			$fields["btnEliminar"]='<li><a href="borrar.php?cuponid='.$_GET["cuponid"].'&tipoOpcion='.$fields["tipoOpcion"].'" class="button tiny alert">Eliminar</a></li>';
		}
	}
	if(isset($empresas)){
		$fields["empresas"] = $empresas;
	}
}else{

	header("Location: index.php");

}

$urlPagina="../templates/administracion_cuerpo.html";
$fp                        = file_get_contents($urlPagina, "r");
$head                      = preg_replace("#(.*)<head>(.*?)</head>(.*)#is", '$2', $fp);
$fp                        = str_replace($head, "", $fp);

$obj_page                  = new page_class("visorSocial.html", "../templates/");
$obj_page->add_all($fields, "");
$index = $obj_page->get_output();
$fp  = str_replace("<body>", "<body>".$index, $fp);

$obj_page                  = new page_class("searchlog.html", "../templates/inc");
$obj_page->add_all($fields, "");
$searchlog = $obj_page->get_output();
$fp  = str_replace("<body>", "<body>".$searchlog, $fp);

$obj_page                  = new page_class("header-index.html", "../templates/inc");
$obj_page->add_all($fields, "");
$header = $obj_page->get_output();
$fp  = str_replace("</head><body>", $header, $fp);

$obj_page                  = new page_class("footer-index.html", "../templates/inc");
$obj_page->add_all($fields, "");
$fin = $obj_page->get_output();
$fp = str_replace("</body>", $fin . '</body>', $fp);

$fp = str_replace('onload="loadX()"', "", $fp);
$fp = str_replace("<title>PassClub</title>", "<title>".$fields["titulo"]."</title>", $fp);
$fieldsHtml["contenidoPagina"] = $fp;
$obj_page = new page_class("administracion.html", "../templates");
$obj_page->add_all($fieldsHtml, "");
$obj_page->display_output();
?>