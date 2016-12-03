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
extract($_POST);
$mensajeSalida="";

if(isset($action) && isset($_GET["page"]) &&  isset($accionExcel)){

	if ($action == "upload"){
//cargamos el archivo al servidor con el mismo nombre

//solo le agregue el sufijo bak_

		$archivo = $_FILES['excel']['name'];

		$tipo = $_FILES['excel']['type'];

		$destino = "bak_".$archivo;
		if($destino!="bak_"){
			if (copy($_FILES['excel']['tmp_name'],$destino)){ 
			//echo "Archivo Cargado Con Éxito";
			//$mensajeSalida="swal('Here a message!')";
			}else{
				
			}

			if (file_exists ("bak_".$archivo)){

				/** Clases necesarias */

				require_once("../clases/PHPExcel.php");

				require_once("../clases/PHPExcel/Reader/Excel2007.php");

	// Cargando la hoja de cálculo

				$objReader = new PHPExcel_Reader_Excel2007();

				$objPHPExcel = $objReader->load("bak_".$archivo);

				$objFecha = new PHPExcel_Shared_Date();

	// Asignar hoja de excel activa

				$objPHPExcel->setActiveSheetIndex(0);


	// Llenamos el arreglo con los datos  del archivo xlsx
				$j=0;
				if($accionExcel=="crear"){
					if($_GET["page"]=="empresas"){
						for ($i=3;$i<=($objPHPExcel->getActiveSheet()->getHighestRow());$i++){

							$_DATOS_EXCEL[$j]['nombre'] = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getCalculatedValue();

							$_DATOS_EXCEL[$j]['direccion']= $objPHPExcel->getActiveSheet()->getCell("B".$i)->getCalculatedValue();

							$_DATOS_EXCEL[$j]['ciudadRelacionada'] = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getCalculatedValue();

							$_DATOS_EXCEL[$j]['ciudad'] = $objPHPExcel->getActiveSheet()->getCell("D".$i)->getCalculatedValue();

							$_DATOS_EXCEL[$j]['tipo']= $objPHPExcel->getActiveSheet()->getCell("E".$i)->getCalculatedValue();

							$_DATOS_EXCEL[$j]['telefono'] = $objPHPExcel->getActiveSheet()->getCell("F".$i)->getCalculatedValue();

							$_DATOS_EXCEL[$j]['web']= $objPHPExcel->getActiveSheet()->getCell("G".$i)->getCalculatedValue();

							$_DATOS_EXCEL[$j]['logo'] = $objPHPExcel->getActiveSheet()->getCell("H".$i)->getCalculatedValue();
							
							$_DATOS_EXCEL[$j]['latitud']= $objPHPExcel->getActiveSheet()->getCell("I".$i)->getCalculatedValue();

							$_DATOS_EXCEL[$j]['longitud']= $objPHPExcel->getActiveSheet()->getCell("J".$i)->getCalculatedValue();

							$_DATOS_EXCEL[$j]['ayuda']= $objPHPExcel->getActiveSheet()->getCell("K".$i)->getCalculatedValue();

							$_DATOS_EXCEL[$j]['facebook']= $objPHPExcel->getActiveSheet()->getCell("L".$i)->getCalculatedValue();
							$j++;
						}
					}else if($_GET["page"]=="usuarios"){
						for ($i=3;$i<=($objPHPExcel->getActiveSheet()->getHighestRow());$i++){

							$_DATOS_EXCEL[$j]['rut'] = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getCalculatedValue();

							$_DATOS_EXCEL[$j]['nombre']= $objPHPExcel->getActiveSheet()->getCell("B".$i)->getCalculatedValue();

							$_DATOS_EXCEL[$j]['mail'] = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getCalculatedValue();

							$_DATOS_EXCEL[$j]['fono']= $objPHPExcel->getActiveSheet()->getCell("D".$i)->getCalculatedValue();

							$_DATOS_EXCEL[$j]['password'] = $objPHPExcel->getActiveSheet()->getCell("E".$i)->getCalculatedValue();

							$_DATOS_EXCEL[$j]['fechaNac']= $objPHPExcel->getActiveSheet()->getCell("F".$i)->getCalculatedValue();

							$_DATOS_EXCEL[$j]['direccion'] = $objPHPExcel->getActiveSheet()->getCell("G".$i)->getCalculatedValue();

							$_DATOS_EXCEL[$j]['ciudad']= $objPHPExcel->getActiveSheet()->getCell("H".$i)->getCalculatedValue();
							$j++;
						}
					}
				}else{
					if($_GET["page"]=="empresas"){
						for ($i=3;$i<=($objPHPExcel->getActiveSheet()->getHighestRow());$i++){

							$_DATOS_EXCEL[$j]['nombre'] = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getCalculatedValue();
							$j++;
						

						}
					}else if($_GET["page"]=="usuarios"){
						for ($i=3;$i<=($objPHPExcel->getActiveSheet()->getHighestRow());$i++){

							$_DATOS_EXCEL[$j]['rut'] = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getCalculatedValue();

							$j++;
						}
					}
				}
				if($_GET["page"]=="empresas" || $_GET["page"]=="usuarios"){
					$_SESSION["accionExcel"]=$accionExcel;
					$_SESSION["arregloExcel"]=$_DATOS_EXCEL;
					$_SESSION["arregloExcelTipo"]=$_GET["page"];
					$mensajeSalida="
					swal({   
						title: 'Estas seguro?',   
						text: 'El archivo se subira a la base de datos',   
						type: 'warning',   
						showCancelButton: true,   
						confirmButtonColor: '#DD6B55',   
						confirmButtonText: 'Subir',   
						cancelButtonText: 'Cancelar',
						closeOnConfirm: false }, 
						function(){   
							location.href='subirExcelDB.php';
						});";
}else{
	$mensajeSalida="swal({   
		title: 'Error!',   
		text: 'Error en url',   
		type: 'error',   
		confirmButtonText: 'Ok' });";
}
}else{
	$mensajeSalida="swal({   
		title: 'Error!',   
		text: 'Primero debes cargar el archivo!',   
		type: 'error',   
		confirmButtonText: 'Ok' });";
}

$errores=0;


	//una vez terminado el proceso borramos el

	//archivo que esta en el servidor el bak_

unlink($destino);
}else{
	$mensajeSalida="swal({   
		title: 'Error!',   
		text: 'Error Al Cargar el Archivo!',   
		type: 'error',   
		confirmButtonText: 'Ok' });";
}
}
}
if($_GET["page"]=="empresas"){
	$li='<li>Subir logos</li>';
}else{
	$li="";
}
$urlPagina="../templates/subirExcel.html";
$fp                        = file_get_contents($urlPagina, "r");
$head                      = preg_replace("#(.*)<head>(.*?)</head>(.*)#is", '$2', $fp);
$fp                        = str_replace($head, "", $fp);

$obj_page                  = new page_class("header-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$enc = $obj_page->get_output();
$fp  = str_replace("</head><body>", $enc, $fp);

$obj_page                  = new page_class("footer-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$fin = $obj_page->get_output();
$fp = str_replace("</body>", $fin . '</body>', $fp);
$fp = str_replace('<div id="paso3"></div>', $li, $fp);
$fp = str_replace("<script id='alertaSuave'></script>", "<script id='alertaSuave'>
	window.onload=function(){".$mensajeSalida."}</script>", $fp);
$fp = str_replace("<title>PassClub</title>", "<title>Subir Excels en PassClub</title>", $fp);
$fields["contenidoPagina"] = $fp;
$obj_page = new page_class("administracion.html", "../templates");
$obj_page->add_all($fields, "");
$obj_page->display_output();

?>