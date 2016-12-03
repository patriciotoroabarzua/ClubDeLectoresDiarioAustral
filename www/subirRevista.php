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
if (isset($_POST['subirBtn'])) {
	$titulo=$_POST["titulo"];
	$ciudad=$_POST["ciudad"];
	$revista=$_POST["revista"];
	mysql_connect($_HOSTDB,$_USERDB,$_PASSDB) or die ('Ha fallado la conexión: '.mysql_error());
	mysql_select_db($_NAMEDB) or die ('Error al seleccionar la Base de Datos: '.mysql_error());

	$mensaje=mysql_query("INSERT INTO revista (titulo, revista, ciudad) VALUES ('".$titulo."', '".$revista."', '".$ciudad."');");
	if($mensaje==1){
		$_SESSION["mensajeTipo"]="exito";
		$_SESSION["mensajeSalida"]= "Revista cargada con exito";
	}else{
		$_SESSION["mensajeTipo"]="error";
		$_SESSION["mensajeSalida"]= "Error al cargar Revista";
	}

	mysql_close();
	header("Location: subirRevista.php");
}



$urlPagina="../templates/administracion_cuerpo.html";
$fp                        = file_get_contents($urlPagina, "r");
$head                      = preg_replace("#(.*)<head>(.*?)</head>(.*)#is", '$2', $fp);
$fp                        = str_replace($head, "", $fp);
$obj_page                  = new page_class("header-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$enc = $obj_page->get_output();
$fp  = str_replace("</head><body>", $enc, $fp);

$obj_page                  = new page_class("subirRevista.html", "../templates/");
$obj_page->add_all($fields, "");
$body = $obj_page->get_output();
$fp = str_replace("</body>", $body , $fp);
$obj_page                  = new page_class("footer-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$fin = $obj_page->get_output();
$fp = str_replace("</body>", $fin . '</body>', $fp);
$fp = str_replace("<title>PassClub</title>", "<title>Subir Revistas en PassClub</title>", $fp);
$fieldsHtml["contenidoPagina"] = $fp;
$obj_page = new page_class("administracion.html", "../templates");
$obj_page->add_all($fieldsHtml, "");
$obj_page->display_output();

?>