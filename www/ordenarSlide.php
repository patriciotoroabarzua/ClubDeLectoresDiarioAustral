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
if (isset($_POST['arregloInc'])) {
	for($i=1;$i<7;$i++){
		if($_POST['slide'.$i]!="no"){
			$arrayNewSlide[$i-1]["idCupon"]=$_POST['slide'.$i];
			$arrayNewSlide[$i-1]["posicion"]=$i;
		}
	}
	$result=actualizarSlide($arrayNewSlide,$_POST['ciudad']);
	if($result==1){
		$_SESSION["mensajeTipo"]="exito";
		$_SESSION["mensajeSalida"]= "SlideShow actualizado";
		
	}else{
		$_SESSION["mensajeTipo"]="error";
		$_SESSION["mensajeSalida"]= "SlideShow No Actualizado";
	}
	return 0;
}
if(isset($_GET["slide_ciudad"])){
	$ciudadSlide=$_GET["slide_ciudad"];
	$array = getSlide($ciudadSlide);
	$fields["slide"]=mostrarSlide($array);
	$arrayPosibles=distintoSlide(getSlideDisponible($ciudadSlide),$array);
	$fields["ordenarSlidePosibles"]=ordenarSlideSelect($arrayPosibles);
}else{
	$_SESSION["mensajeTipo"]="error";
	$_SESSION["mensajeSalida"]= "ciudad erronea";
	header("location: gestorCupones.php");
}

$urlPagina="../templates/administracion_cuerpo.html";
$fp                        = file_get_contents($urlPagina, "r");
$head                      = preg_replace("#(.*)<head>(.*?)</head>(.*)#is", '$2', $fp);
$fp                        = str_replace($head, "", $fp);

$obj_page                  = new page_class("header-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$enc = $obj_page->get_output();
$fp  = str_replace("</head><body>", $enc, $fp);

$obj_page                  = new page_class("ordenarSlide.html", "../templates/");
$obj_page->add_all($fields, "");
$body = $obj_page->get_output();
$fp  = str_replace("</body>", $body, $fp);

$obj_page                  = new page_class("footer-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$fin = $obj_page->get_output();
$fp = str_replace("</body>", $fin . '</body>', $fp);
$fp = str_replace("<title>PassClub</title>", "<title>Ordenar SlideShow</title>", $fp);

$fields["contenidoPagina"] = $fp;
$obj_page = new page_class("administracion.html", "../templates");
$obj_page->add_all($fields, "");
$obj_page->display_output();

?>
