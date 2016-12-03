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
if($_SESSION["IDCUPONCREADO"] == ''){
	header("location: gestorCupones.php");
}
if (isset($_POST['subirBtn'])) {
	$fields["mensaje"]="";
	mysql_connect($_HOSTDB,$_USERDB,$_PASSDB) or die ('Ha fallado la conexión: '.mysql_error());
	mysql_select_db($_NAMEDB) or die ('Error al seleccionar la Base de Datos: '.mysql_error());
	for($i=0;$i<count($_FILES["imagen"]["name"]);$i++){
		$imagen["name"]=$_FILES["imagen"]["name"][$i];
		$imagen["type"]=$_FILES["imagen"]["type"][$i];
		$imagen["tmp_name"]=$_FILES["imagen"]["tmp_name"][$i];
		$imagen["error"]=$_FILES["imagen"]["error"][$i];
		$imagen["size"]=$_FILES["imagen"]["size"][$i];

		$subir= new imgUpldr;
		$urlImg[$i]=$subir->init($imagen);
		if(substr($urlImg[$i], 0, 3)=="img" && $_SESSION["IDCUPONCREADO"] != ''){
			$mensaje=mysql_query("INSERT INTO imagen (imagen, Cuponid) VALUES ('".$urlImg[$i]."', '".$_SESSION["IDCUPONCREADO"]."');");
			if($mensaje==1){
				$_SESSION["mensajeTipo"]="exito";
				$_SESSION["mensajeSalida"]= "Imagen subida con exito";
			}else{
				$_SESSION["mensajeTipo"]="error";
				$_SESSION["mensajeSalida"]= "Imagen NO subida";
			}
		}else{
			$_SESSION["mensajeTipo"]="error";
			$_SESSION["mensajeSalida"]= $urlImg[$i];
		}
		unset($subir);
		unset($imagen);
		sleep(1);
	}
	mysql_close();
	header("Location: subir_img.php");
}



$urlPagina="../templates/administracion_cuerpo.html";
$fp                        = file_get_contents($urlPagina, "r");
$head                      = preg_replace("#(.*)<head>(.*?)</head>(.*)#is", '$2', $fp);
$fp                        = str_replace($head, "", $fp);
$obj_page                  = new page_class("header-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$enc = $obj_page->get_output();
$fp  = str_replace("</head><body>", $enc, $fp);

$obj_page                  = new page_class("subirImg.html", "../templates/");
$obj_page->add_all($fields, "");
$body = $obj_page->get_output();
$fp = str_replace("</body>", $body , $fp);
$obj_page                  = new page_class("footer-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$fin = $obj_page->get_output();
$fp = str_replace("</body>", $fin . '</body>', $fp);
$fieldsHtml["contenidoPagina"] = $fp;
$obj_page = new page_class("administracion.html", "../templates");
$obj_page->add_all($fieldsHtml, "");
$obj_page->display_output();

?>