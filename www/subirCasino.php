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

if(isset($action) && $fechaDesde!="" && $fechaHasta!="" && $cantidad!=""){
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

				$abre = fopen('casino.csv', 'w+');


					for ($i=2;$i<=($objPHPExcel->getActiveSheet()->getHighestRow());$i++){

						$_DATOS_EXCEL['codigo'][$j]= $objPHPExcel->getActiveSheet()->getCell("B".$i)->getCalculatedValue();
						$linea = $_DATOS_EXCEL['codigo'][$j].",".$fechaDesde.",".$fechaHasta.",".$cantidad."\r\n"; 
    					fwrite($abre, $linea);
						$j++;
					}

					fclose($abre);
				
					$_SESSION["arregloExcel"]="hola";
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
							location.href='subirExcelCasinoDB.php';
						});";
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

$urlPagina="../templates/administracion_cuerpo.html";
$fp                        = file_get_contents($urlPagina, "r");
$head                      = preg_replace("#(.*)<head>(.*?)</head>(.*)#is", '$2', $fp);
$fp                        = str_replace($head, "", $fp);

$obj_page                  = new page_class("header-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$enc = $obj_page->get_output();
$fp  = str_replace("</head><body>", $enc, $fp);

$obj_page                  = new page_class("subirCasino.html", "../templates/");
$obj_page->add_all($fields, "");
$body = $obj_page->get_output();
$fp = str_replace("</body>", $body , $fp);

$obj_page                  = new page_class("footer-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$fin = $obj_page->get_output();
$fp = str_replace("</body>", $fin . '</body>', $fp);

$fp = str_replace("<script id='alertaSuave'></script>", "<script id='alertaSuave'>
	window.onload=function(){".$mensajeSalida."}</script>", $fp);
$fp = str_replace("<title>PassClub</title>", "<title>Subir Codigos Casino en PassClub</title>", $fp);
$fieldsHtml["contenidoPagina"] = $fp;
$obj_page = new page_class("administracion.html", "../templates");
$obj_page->add_all($fieldsHtml, "");
$obj_page->display_output();

?>