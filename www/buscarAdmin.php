<?php
include '../config/config.php';
if(isset($_SESSION['tipo'])){
	if($_SESSION['tipo']=='usuario'){
		header("location: index.php");
	}
}else{
	header("location: index.php");
}

if(isset($_GET["tituloBuscado"])){
	$blocks["resultadosBusqueda"]=searchCupon($_GET["tituloBuscado"],"","");
	//m_array($fields["resultadosBusqueda"]);
	
}


$urlPagina="../templates/administracion_cuerpo.html";
$fp                        = file_get_contents($urlPagina, "r");
$head                      = preg_replace("#(.*)<head>(.*?)</head>(.*)#is", '$2', $fp);
$fp                        = str_replace($head, "", $fp);

$obj_page                  = new page_class("buscarAdmin.html", "../templates/");
$obj_page->add_all($fields, $blocks);
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
if(!isset($blocks["resultadosBusqueda"])){
		
		$fp  = str_replace(">publicado por <", "><", $fp);
		$fp  = str_replace(">ver<", "><", $fp);
		$fp  = str_replace(">eliminar<", "><", $fp);
		echo "<div style='padding: 0.5625rem;
       text-align: center;
       color: #FFF;
       background: #FF3939;
       font-size: .9rem;
       text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);'>0 Resultados encontrados</div>";

	}else{
		echo "<div style='padding: 0.5625rem;
       text-align: center;
       color: #FFF;
       background: #67b918;
       font-size: .9rem;
       text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);'>".count($blocks["resultadosBusqueda"])." Resultados encontrados</div>";
	}
$fp = str_replace("<title>PassClub</title>", "<title>Buscador</title>", $fp);
$fields["contenidoPagina"] = $fp;
$obj_page = new page_class("administracion.html", "../templates");
$obj_page->add_all($fields, "");
$obj_page->display_output();
?>