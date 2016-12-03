<?php
include '../config/config.php';
$fields["vacio"] = "si";
alertaMaxima();
	//mail('nico@huellaproducciones.cl', 'prueba', "mensajefinaldelamuerte");
$textoCiudad="";
$textoCiudadReemplazo="";
if(isset($_GET["ciudad"])){
	$ciudad=$_GET["ciudad"];
	$array = getAllCupon($ciudad);
	$empresas=getAllEmpresas($ciudad);
	$fields["Titulo"]=$ciudad;
	$slide=getSlide($ciudad);
	$ads=getPublicidad($ciudad);
	if($_GET["ciudad"]=="puerto montt"){	
		$textoCiudad='id="menu-puertomontt"';
	}else{
		$textoCiudad='id="menu-'.strtolower($_GET["ciudad"]).'"';
	}
	$textoCiudadReemplazo=$textoCiudad." class='active'";

	if($ciudad=="temuco"){
		if(isset($_SESSION['tipo'])){
			if(descargaCasino()){
				$fields["casinoIco"]="img/casino_descarga.jpg";
				$fields["casinoUrl"]="generarPDFCasino.php";
			}else{
				$fields["casinoIco"]="img/casino_nodisponible.jpg";
				$fields["casinoUrl"]="#";
			}
		}else{
			$fields["casinoIco"]="img/casino_ingresa.jpg";
			$fields["casinoUrl"]="login.php";
		}
		$fields["casinoTemuco"]='<div class="panel alert panel-ads">
			<a href="'.$fields["casinoUrl"].'" class="casino-ads">
				<img src="'.$fields["casinoIco"].'">
			</a>
		</div>';
	}else{
		$fields["casinoTemuco"]='<div class="panel alert panel-ads">
			<a href="https://www.facebook.com/clubdelectores" class="casino-ads" target="_blank">
				<img src="img/300x130.jpg">
			</a>
		</div>';
		
	}

}else{
	$array = getAllCupon("");
	$empresas=getAllEmpresas("");
	$fields["Titulo"]="Todos";
	$slide=getSlide("todas");
	$ads=getPublicidad("todas");
	$textoCiudad='id="menu-todas"';
	$textoCiudadReemplazo='id="menu-todas" class="active"';
		$fields["casinoTemuco"]='<div class="panel alert panel-ads">
			<a href="https://www.facebook.com/clubdelectores" class="casino-ads" target="_blank">
				<img src="img/300x130.jpg">
			</a>
		</div>';
}


$beneficios=getCuponesByType($array,"beneficio",4);
$panoramas=getCuponesByType($array,"panorama",4);
$promociones=getCuponesByType($array,"promocion",4);
$cursos=getCuponesByType($array,"curso",4);
$concursos=getCuponesByType($array,"concurso",4);
$sociales=getCuponesByType($array,"social",4);
$blocks["panoramas"]=$panoramas;
$blocks["promociones"]=$promociones;
$blocks["cursos"]=$cursos;
$blocks["concursos"]=$concursos;
$blocks["sociales"]=$sociales;
$blocks["agenda"]= getAgenda($array);
$blocks["slideShow"]=$slide;
$blocks["beneficios"]=$beneficios;
for($j=0;$j<count($ads);$j++){
	$fields["espacioPublicidad".($j+1)]=ordenarPublicidad($ads[$j]);
}
if(isset($empresas)){
	$fields["empresas"] = $empresas;
}

$urlPagina="../templates/administracion_cuerpo.html";
$fp                        = file_get_contents($urlPagina, "r");
$head                      = preg_replace("#(.*)<head>(.*?)</head>(.*)#is", '$2', $fp);
$fp                        = str_replace($head, "", $fp);

$obj_page                  = new page_class("index.html", "../templates/");
$obj_page->add_all($fields, $blocks);
$index = $obj_page->get_output();
$fp  = str_replace("<body>", "<body>".$index, $fp);

$obj_page                  = new page_class("cupons-map.html", "../templates/inc");
$obj_page->add_all($fields, "");
$maps = $obj_page->get_output();
$fp  = str_replace("<body>", "<body>".$maps, $fp);

$obj_page                  = new page_class("searchlog.html", "../templates/inc");
$obj_page->add_all($fields, "");
$searchlog = $obj_page->get_output();
$fp  = str_replace("<body>", "<body>".$searchlog, $fp);


$obj_page                  = new page_class("slider.html", "../templates/inc");
$obj_page->add_all("", $blocks);
$sliders = $obj_page->get_output();
$fp  = str_replace("<body>", "<body>".$sliders, $fp);

$obj_page                  = new page_class("header-index.html", "../templates/inc");
$obj_page->add_all($fields, "");
$header = $obj_page->get_output();
$fp  = str_replace("<body>", $header, $fp);

$obj_page                  = new page_class("footer-index.html", "../templates/inc");
$obj_page->add_all($fields, "");
$fin = $obj_page->get_output();
$fp = str_replace("</body>", $fin . '</body>', $fp);

$fp = str_replace($textoCiudad, $textoCiudadReemplazo, $fp);
$fieldsHtml["contenidoPagina"] = $fp;
$obj_page = new page_class("administracion.html", "../templates");
$obj_page->add_all($fieldsHtml, "");
$obj_page->display_output();

?>