<?php
include '../config/config.php';
include '../clases/subirImgClass.php';
if(isset($_SESSION['tipo'])){
	if($_SESSION['tipo']=='usuario'){
		header("location: index.php");
	}
}else{
	header("location: index.php");
}
alertaMaxima();
if (isset($_POST['subirBtn'])) {
	$subir= new imgUpldr;
	$urlImg=$subir->init($_FILES['imagen']);

	if(substr($urlImg, 0, 3)=="img"){
		$result=actualizarPublicidad($urlImg,$_POST['zona'],$_POST['espacio'],$_POST['url']);
		if($result>1){
			$_SESSION["mensajeTipo"]="exito";
			$_SESSION["mensajeSalida"]= "Actualizado con exito";
		}else{
			$_SESSION["mensajeTipo"]="error";
			$_SESSION["mensajeSalida"]= "Error en la actualización";
		}
	}else{
		$_SESSION["mensajeTipo"]="error";
		$_SESSION["mensajeSalida"]= $urlImg;
	}

	header("Location: subir_publicidad.php");
}
$urlPagina="../templates/administracion_cuerpo.html";
$fp                        = file_get_contents($urlPagina, "r");
$head                      = preg_replace("#(.*)<head>(.*?)</head>(.*)#is", '$2', $fp);
$fp                        = str_replace($head, "", $fp);

$obj_page                  = new page_class("header-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$enc = $obj_page->get_output();
$fp  = str_replace("</head><body>", $enc, $fp);

$obj_page                  = new page_class("subirPublicidad.html", "../templates/");
$obj_page->add_all($fields, "");
$body = $obj_page->get_output();
$fp  = str_replace("</body>", $body."</body>", $fp);



$obj_page                  = new page_class("footer-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$fin = $obj_page->get_output();
$fp = str_replace("</body>", $fin . '</body>', $fp);
$fp = str_replace("<title>PassClub</title>", "<title>Subir Publicidad en PassClub</title>", $fp);
$fields["contenidoPagina"] = $fp;
$obj_page = new page_class("administracion.html", "../templates");
$obj_page->add_all($fields, "");
$obj_page->display_output();
?>