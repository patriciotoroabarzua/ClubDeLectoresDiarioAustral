<?php
include '../config/config.php';
alertaMaxima();
if (isset($_POST['revisarCorreo'])) {
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");
	$mensaje=mysqli_query($con,"SELECT * FROM usuario WHERE mail='".$_POST["mail"]."'");
	$row = mysqli_fetch_array($mensaje);
	$largo=mysqli_num_rows($mensaje);
	if($largo>0){
		$mensajeSalida="Hola ".$row["nombre"]." Tu password de acceso a PassClub es: ".$row["password"];
		echo mail($_POST["mail"], "Recuperar Password", $mensajeSalida);
		$_SESSION["mensajeTipo"]="exito";
		$_SESSION["mensajeSalida"]= "Mensaje enviado";

	}else{
		$_SESSION["mensajeTipo"]="error";
		$_SESSION["mensajeSalida"]= "El mail no se encuentra en la base de datos";
	}

	mysqli_close($con);
	header("location: login.php");
}


$urlPagina="../templates/administracion_cuerpo.html";
$fp                        = file_get_contents($urlPagina, "r");
$head                      = preg_replace("#(.*)<head>(.*?)</head>(.*)#is", '$2', $fp);
$fp                        = str_replace($head, "", $fp);
$obj_page                  = new page_class("header-login.html", "../templates/inc");
$obj_page->add_all("", "");
$enc = $obj_page->get_output();
$fp  = str_replace("</head><body>", $enc, $fp);

$obj_page                  = new page_class("recuperarContrasena.html", "../templates/");
$obj_page->add_all($fields, "");
$body = $obj_page->get_output();
$fp = str_replace("</body>", $body , $fp);
$obj_page                  = new page_class("footer-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$fin = $obj_page->get_output();
$fp = str_replace("</body>", $fin . '</body>', $fp);
$fp = str_replace("<title>Login - PassClub</title>", "<title>Recuperar Contraseña</title>", $fp);
$fieldsHtml["contenidoPagina"] = $fp;
$obj_page = new page_class("administracion.html", "../templates");
$obj_page->add_all($fieldsHtml, "");
$obj_page->display_output();

?>