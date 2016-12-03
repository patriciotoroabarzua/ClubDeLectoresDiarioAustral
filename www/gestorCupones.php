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
$_SESSION["IDCUPONCREADO"]="";
$urlPagina="../templates/gestorCupones.html";
$fp                        = file_get_contents($urlPagina, "r");
$head                      = preg_replace("#(.*)<head>(.*?)</head>(.*)#is", '$2', $fp);
$fp                        = str_replace($head, "", $fp);
$obj_page                  = new page_class("header-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$enc = $obj_page->get_output();
$fp  = str_replace("<head></head>", $enc, $fp);
$obj_page                  = new page_class("footer-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$fin = $obj_page->get_output();
$fp = str_replace("</body>", $fin . '</body>', $fp);
$fp = str_replace("<title>PassClub</title>", "<title>Administracion en PassClub</title>", $fp);
$fields["contenidoPagina"] = $fp;
$obj_page = new page_class("administracion.html", "../templates");
$obj_page->add_all($fields, "");
$obj_page->display_output();
?>