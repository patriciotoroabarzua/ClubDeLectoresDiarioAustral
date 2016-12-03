<?php
include '../config/config.php';
if(isset($_SESSION['tipo'])){

}else{
	header("location: index.php");
}
alertaMaxima();
$fieldsBody["nombre"]=$_SESSION["nombre"];
$fieldsBody["rut"]=$_SESSION["rut"];
$fieldsBody["direccion"]=$_SESSION["direccion"];
$fieldsBody["mail"]=$_SESSION["mail"];
$fieldsBody["fono"]=$_SESSION["fono"];
$fieldsBody["fechaNac"]=$_SESSION["fechaNac"];

if(getCantidadHistorialEdit($_SESSION["rut"])>0){
	$fieldsBody["sinEditar"]="";
}else{
	$fieldsBody["sinEditar"]='<div class="panel" style="background:rgb(255, 255, 107);border-top:1px solid rgb(224, 224, 2); border-bottom:1px solid rgb(224, 224, 2);">
			<p style="text-shadow:0 1px 0 rgba(255,255,255,.5);">Para finalizar el proceso de registro, <span style="text-decoration:underline;">completa tu información de perfil</span> <a  href="editorUsuario.php" class="button tiny radius" style="text-shadow:none;margin:0 0 0 10px;">editar perfil</a></p>
		</div>';
}

$urlPagina="../templates/administracion_cuerpo.html";
$fp                        = file_get_contents($urlPagina, "r");
$head                      = preg_replace("#(.*)<head>(.*?)</head>(.*)#is", '$2', $fp);
$fp                        = str_replace($head, "", $fp);
$obj_page                  = new page_class("visorPerfilUsuario.html", "../templates/");
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
$fp = str_replace("<title>PassClub</title>", "<title>Perfil de Usuario</title>", $fp);
$fields["contenidoPagina"] = $fp;
$obj_page = new page_class("administracion.html", "../templates");
$obj_page->add_all($fields, "");
$obj_page->display_output();
?>