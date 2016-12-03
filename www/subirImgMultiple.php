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
	$fields["mensaje"]="";
	mysql_connect($_HOSTDB,$_USERDB,$_PASSDB) or die ('Ha fallado la conexión: '.mysql_error());
	mysql_select_db($_NAMEDB) or die ('Error al seleccionar la Base de Datos: '.mysql_error());
	for($i=0;$i<count($_FILES["imagen"]["name"]);$i++){
		$imagen["name"]=$_FILES["imagen"]["name"][$i];
		$imagen["type"]=$_FILES["imagen"]["type"][$i];
		$imagen["tmp_name"]=$_FILES["imagen"]["tmp_name"][$i];
		$imagen["error"]=$_FILES["imagen"]["error"][$i];
		$imagen["size"]=$_FILES["imagen"]["size"][$i];
		$idEmpresa = getIdEmpresa(substr($imagen["name"],0,-4));
		if($idEmpresa>0){
			$subir= new imgUpldr;
			$urlImg=$subir->init($imagen);
			if(substr($urlImg, 0, 3)=="img"){
				$mensaje=mysql_query("UPDATE empresa SET logo='".$urlImg."' WHERE id=".$idEmpresa);
				if($mensaje==1){
					$_SESSION["mensajeTipo"]="exito";
					$_SESSION["mensajeSalida"]= "Carga con exito";
				}else{
					$_SESSION["mensajeTipo"]="error";
					$_SESSION["mensajeSalida"]= "Error al cargar imagen";
				}

			}else{
				$_SESSION["mensajeTipo"]="error";
				$_SESSION["mensajeSalida"]= $urlImg;
			}
		}
		sleep(1);
	}
	mysql_close();
	header("location: subirImgMultiple.php");
}

$urlPagina="../templates/administracion_cuerpo.html";
$fp                        = file_get_contents($urlPagina, "r");
$head                      = preg_replace("#(.*)<head>(.*?)</head>(.*)#is", '$2', $fp);
$fp                        = str_replace($head, "", $fp);
$obj_page                  = new page_class("header-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$enc = $obj_page->get_output();
$fp  = str_replace("</head><body>", $enc, $fp);

$obj_page                  = new page_class("subirImgMultiple.html", "../templates/");
$obj_page->add_all($fields, "");
$body = $obj_page->get_output();
$fp = str_replace("</body>", $body , $fp);
$obj_page                  = new page_class("footer-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$fin = $obj_page->get_output();
$fp = str_replace("</body>", $fin . '</body>', $fp);
$fp = str_replace("<title>PassClub</title>", "<title>Subir Imagenes en PassClub</title>", $fp);
$fieldsHtml["contenidoPagina"] = $fp;
$obj_page = new page_class("administracion.html", "../templates");
$obj_page->add_all($fieldsHtml, "");
$obj_page->display_output();

?>