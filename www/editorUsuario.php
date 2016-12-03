<?php
include '../config/config.php';
if(isset($_SESSION['tipo'])){

}else{
	header("location: index.php");
}
alertaMaxima();
setHistorial("", $_SESSION["rut"], "editar");
$fieldsBody["nombre"]=Ntildes($_SESSION["nombre"]);
$fieldsBody["rut"]=$_SESSION["rut"];
$fieldsBody["direccion"]=Ntildes($_SESSION["direccion"]);
$fieldsBody["mail"]=$_SESSION["mail"];
$fieldsBody["fono"]=$_SESSION["fono"];
$fieldsBody["fechaNac"]=$_SESSION["fechaNac"];

$urlPagina="../templates/administracion_cuerpo.html";
$fp                        = file_get_contents($urlPagina, "r");
$head                      = preg_replace("#(.*)<head>(.*?)</head>(.*)#is", '$2', $fp);
$fp                        = str_replace($head, "", $fp);

$obj_page                  = new page_class("editorUsuario.html", "../templates/");
$obj_page->add_all($fieldsBody, "");
$body = $obj_page->get_output();
$fp = str_replace("<body>", '<body>'.$body , $fp);

$obj_page                  = new page_class("header-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$enc = $obj_page->get_output();

if($_SESSION["tipo"]=="usuario"){
	$enc = str_replace('<li><a href="gestorCupones.php">Administración</a></li>', "", $enc);
}

$fp  = str_replace("</head><body>", $enc, $fp);



$obj_page                  = new page_class("footer-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$fin = $obj_page->get_output();
$fp = str_replace("</body>", $fin . '</body>', $fp);
$fp = str_replace("<title>PassClub</title>", "<title>Editor de Usuario en PassClub</title>", $fp);
$fields["contenidoPagina"] = $fp;
$obj_page = new page_class("administracion.html", "../templates");
$obj_page->add_all($fields, "");
$obj_page->display_output();
?>