<?php
include '../config/config.php';
alertaMaxima();
if(isset($_GET["erro"])){
	if($_GET["erro"]=="contrasena"){
			echo "<div style='padding: 0.5625rem;
			text-align: center;
			color: #FFF;
			background: #FF3939;
			font-size: .9rem;
			text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);'>Contraseña Incorrecta</div>";

		}else{
			echo "<div style='padding: 0.5625rem;
			text-align: center;
			color: #FFF;
			background: #FF3939;
			font-size: .9rem;
			text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);'>Usuario Incorrecto</div>";
		}
}
$urlPagina="../templates/administracion_cuerpo.html";
$fp                        = file_get_contents($urlPagina, "r");
$head                      = preg_replace("#(.*)<head>(.*?)</head>(.*)#is", '$2', $fp);
$fp                        = str_replace($head, "", $fp);


$obj_page                  = new page_class("header-login.html", "../templates/inc");
$obj_page->add_all("", "");
$enc = $obj_page->get_output();
$fp  = str_replace("</head><body>", $enc, $fp);

$obj_page                  = new page_class("login.html", "../templates/");
$obj_page->add_all("", "");
$body = $obj_page->get_output();
$fp  = str_replace("</body>", $body."</body>", $fp);



$obj_page                  = new page_class("footer-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$fin = $obj_page->get_output();
$fp = str_replace("</body>", $fin . '</body>', $fp);

$fields["contenidoPagina"] = $fp;
$obj_page = new page_class("administracion.html", "../templates");
$obj_page->add_all($fields, "");
$obj_page->display_output();
?>