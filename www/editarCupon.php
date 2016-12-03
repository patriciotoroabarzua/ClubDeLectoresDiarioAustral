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
if(!isset($_GET["cuponid"]) || !isset($_GET["tipoOpcion"])){
	header("Location: index.php");
}
$fields["tipoOpcion"]=$_GET["tipoOpcion"];
$idCupon=$_GET["cuponid"];
$cupon=getCupon($idCupon);
$fields["id"]=$cupon["id"];
$fields["titulo"]=Ntildes($cupon["titulo"]);
$fields["Empresaid"]=$cupon["Empresaid"];
$fields["lugar"]=Ntildes($cupon["lugar"]);
$fields["telefono"]=$cupon["telefono"];
$fields["auxiliar"]=Ntildes($cupon["auxiliar"]);
$fields["descripcion"]=Ntildes($cupon["descripcion"]);
if($cupon["slide"]=="si"){
	$fields["checkedSi"]="checked";
	$fields["checkedNo"]="";
}else{
	$fields["checkedSi"]="";
	$fields["checkedNo"]="checked";
}
$camposPropios;
$camposPropios2="";
if($fields["tipoOpcion"]=="beneficio"){
	$tipoOpcionOpcionVisible="Beneficio";
	$camposPropios=
	"
	<div class='small-6 columns'>
		<label><h5>Precio Referencial</h5>
			<input name='categoria' type='text' value='".$cupon["categoria"]."'/>
		</label>
	</div>
	<div class='small-6 columns'>
		<label><h5>Descuento</h5>
			<input name='descuento' type='text' value='".$cupon["descuento"]."' />
		</label>
	</div>";
}else if($fields["tipoOpcion"]=="curso"){
	$tipoOpcionOpcionVisible="Curso & Taller";
	$camposPropios=
	"
	<div class='small-6 columns'>
	<label><h5>Fecha</h5>
	<input name='fecha' type='date' value='".$cupon["fecha"]."'/>
	</label>
	</div>
	<div class='small-6 columns'>
	<label><h5>Beneficio</h5>
	<input name='beneficio' type='text' value='".Ntildes($cupon["beneficio"])."' />
	</label>
	</div>";
	$camposPropios2="<div class='row text-left'>
			<div class='small-8 small-centered columns'>
			<div class='row'>
			<div class='small-6 columns'>
			<label><h5>Precio General</h5>
			<input name='precioNormal' type='text' value='".$cupon["precioNormal"]."' />
			</label>

			</div><div class='small-6 columns'>
			<label><h5>Precio Socio</h5>
			<input name='precioSocio' type='text' value='".$cupon["precioSocio"]."' />
			</label>
			</div>
			</div>
			</div>
		</div>
		<div class='row text-left'>
			<div class='small-8 small-centered columns'>
			<div class='row'>
			<div class='small-12 columns'>
			<label><h5>Inscripciones</h5>
			<input name='inscripciones' type='text' value='".Ntildes($cupon["inscripciones"])."' />
			</label>

			</div>
			</div>
			</div>
		</div>
		";
}else if($fields["tipoOpcion"]=="promocion"){
	$tipoOpcionOpcionVisible="Promoción";
	$camposPropios=
	"
	<div class='small-6 columns'>
	<label><h5>Vigencia</h5>
	<input name='vigencia' type='text' value='".Ntildes($cupon["vigencia"])."' />
	</label>
	</div>
	<div class='small-6 columns'>
	<label><h5>Cantidad de Cupones</h5>
	<input name='cuponesDisponibles' type='text' value='".$cupon["cuponesDisponibles"]."'/>
	</label>
	</div>
	";

}else if($fields["tipoOpcion"]=="social"){
	$tipoOpcionOpcionVisible="Social";
	$camposPropios=
	"<div class='small-12 columns'>
	<label><h5>Fecha</h5>
	<input name='fecha' type='date' value='".$cupon["fecha"]."'/>
	</label>
	</div>
	";
}else if($fields["tipoOpcion"]=="panorama"){
	$tipoOpcionOpcionVisible="Panorama";
	$camposPropios=
	"<div class='small-6 columns'>
	<label><h5>Fecha</h5>
	<input name='fecha' type='date' value='".$cupon["fecha"]."'/>
	</label>
	</div>
	<div class='small-6 columns'>
	<label><h5>Beneficio</h5>
	<input name='beneficio' type='text' value='".Ntildes($cupon["beneficio"])."'/>
	</label>
	</div>";
	$camposPropios2="<div class='row text-left'>
			<div class='small-8 small-centered columns'>
			<div class='row'>
			<div class='small-12 columns'>
			<label><h5>Precio Normal</h5>
			<input name='precioNormal' type='text' value='".$cupon["precioNormal"]."' />
			</label>

			</div>
			</div>
			</div>
		</div>
		
		";
}else if($fields["tipoOpcion"]=="concurso"){
	$tipoOpcionOpcionVisible="Concurso";
	$camposPropios=
	"<div class='small-6 columns'>
	<label><h5>Contacto</h5>
	<input name='contacto' type='text' value='".Ntildes($cupon["contacto"])."' />
	</label>
	</div>
	<div class='small-6 columns'>
	<label><h5>Fecha</h5>
	<input name='fecha' type='date' value='".$cupon["fecha"]."'/>
	</label>
	</div>";
}

if (isset($_POST['subirCuponBtn'])) {
	$con=mysqli_connect($_HOSTDB,$_USERDB,$_PASSDB,$_NAMEDB) or die ('Ha fallado la conexión: '.mysql_error());
	$con->query("SET NAMES 'utf8'");
	$titulo=tildesN($_POST["titulo"]);
	$tipoOpcion=($_POST["opcion"]=="Curso & Taller")? "curso": strtolower($_POST["opcion"]);
	$tipoOpcion=($tipoOpcion=="promoción")? "promocion": $tipoOpcion;
	$auxiliar=$_POST["auxiliar"];
	$lugar=($_POST["lugar"]=="")? "NULL": tildesN($_POST["lugar"]);
	$descripcion=tildesN($_POST["descripcion"]);
	$telefono=($_POST["telefono"]=="")? "NULL":$_POST["telefono"];
	$empresa=$_POST["empresa"];
	$slide=$_POST["slide"];
	$id=$_SESSION["id"];
	$idCreado=$idCupon;
	$mensaje=mysqli_query($con,"UPDATE cupon SET titulo = '".$titulo."', descripcion='".$descripcion."', lugar='".$lugar."', telefono='".$telefono."', Usuarioid=".$id.",tipoOpcion='".$tipoOpcion."',Empresaid=".$empresa.",slide='".$slide."',auxiliar='".$auxiliar."' WHERE id='".$idCreado."';");
	if($mensaje==1){
		if($tipoOpcion=="beneficio"){
			$aux1=tildesN($_POST["categoria"]);
			$aux2=tildesN($_POST["descuento"]);
			$mensaje=mysqli_query($con,"UPDATE beneficio SET categoria='".$aux1."', descuento='".$aux2."' WHERE Cuponid='".$idCreado."';");	
		}else if($tipoOpcion=="curso"){
			$aux1=tildesN($_POST["fecha"]);
			$aux2=tildesN($_POST["precioNormal"]);
			$aux3=tildesN($_POST["precioSocio"]);
			$aux4=tildesN($_POST["beneficio"]);
			$aux5=tildesN($_POST["inscripciones"]);
			$mensaje=mysqli_query($con,"UPDATE curso SET fecha='".$aux1."', precioNormal='".$aux2."', precioSocio='".$aux3."', beneficio='".$aux4.", inscripciones='".$aux5."' WHERE Cuponid='".$idCreado."';");	
		}else if($tipoOpcion=="promocion"){
			$aux1=tildesN($_POST["vigencia"]);
			$aux2=tildesN($_POST["cuponesDisponibles"]);
			$mensaje=mysqli_query($con,"UPDATE promocion SET vigencia='".$aux1."', cuponesDisponibles='".$aux2."' WHERE Cuponid='".$idCreado."';");
		}else if($tipoOpcion=="social"){
			$aux1=$_POST["fecha"];
			$mensaje=mysqli_query($con,"UPDATE social SET fecha='".$aux1."' WHERE Cuponid='".$idCreado."';");
		}else if($tipoOpcion=="panorama"){
			$aux1=$_POST["fecha"];
			$aux2=tildesN($_POST["beneficio"]);
			$aux3=tildesN($_POST["precioNormal"]);
			$mensaje=mysqli_query($con,"UPDATE panorama SET fecha='".$aux1."', beneficio='".$aux2."', precioNormal= '".$aux3."' WHERE Cuponid='".$idCreado."';");
		}else if($tipoOpcion=="concurso"){
			$aux1=tildesN($_POST["contacto"]);
			$aux2=$_POST["fecha"];
			$mensaje=mysqli_query($con,"UPDATE concurso SET contacto='".$aux1."', fecha='".$aux2."' WHERE Cuponid='".$idCreado."';");
		}
		if($mensaje==0){
			$mensaje = mysql_error();
		}
	}else{
		$mensaje = mysql_error();
	}
	mysqli_close($con);
	if($mensaje==1){
		$_SESSION["mensajetipoOpcion"]="exito";
		$_SESSION["mensajeSalida"]= "Cupón editado con exito";
		header("Location: visor.php?cuponid=".$idCupon);
	}else{

		$_SESSION["mensajetipoOpcion"]="error";
		$_SESSION["mensajeSalida"]= "Error en la edición del cupon";
		header("Location: editarCupon.php?cuponid=".$idCupon."&tipoOpcion=".$tipoOpcion);
	}
}
$urlPagina="../templates/administracion_cuerpo.html";
$fp                        = file_get_contents($urlPagina, "r");
$head                      = preg_replace("#(.*)<head>(.*?)</head>(.*)#is", '$2', $fp);
$fp                        = str_replace($head, "", $fp);


$obj_page                  = new page_class("header-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$enc = $obj_page->get_output();
$fp  = str_replace("<head></head>", $enc, $fp);

$obj_page                  = new page_class("editarCupon.html", "../templates/");
$obj_page->add_all($fields, "");
$body = $obj_page->get_output();
$fp  = str_replace("<body></body>", $body, $fp);

$obj_page                  = new page_class("footer-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$fin = $obj_page->get_output();

$fp = str_replace('{tipoOpcionOpcion}', $tipoOpcionOpcionVisible, $fp);
$fp = str_replace('<div id="camposPropios"></div>', $camposPropios, $fp);
$fp = str_replace('<div id="camposPropios2"></div>', $camposPropios2, $fp);
$fp = str_replace('function cargarSelect(){', 'function cargarSelect(){' . getAllEmpresas($_SESSION["ciudad"]), $fp);
$fp = str_replace("</body>", $fin . '</body>', $fp);
$fp = str_replace("<title>PassClub</title>", "<title>Editar Cupon</title>", $fp);
$fields["contenidoPagina"] = $fp;
$obj_page = new page_class("administracion.html", "../templates");
$obj_page->add_all($fields, "");
$obj_page->display_output();

?>