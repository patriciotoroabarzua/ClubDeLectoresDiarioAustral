<?php
include '../config/config.php';
if(isset($_SESSION['tipo'])){
	if($_SESSION['tipo']=='usuario'){
		header("location: index.php");
	}
}else{
	header("location: index.php");
}
$fields["tipo"]=$_GET["opcion"];

//$fields["tipo"]="beneficio";
$camposPropios;
$camposPropios2="";
if($fields["tipo"]=="beneficio"){
	$tipoOpcionVisible="Beneficio";
	$camposPropios=
	"
	<div class='small-6 columns'>
		<label><h5>Precio Referencial</h5>
			<input name='categoria' type='text' placeholder='Ej: $1000'/>
		</label>
	</div>
	<div class='small-6 columns'>
		<label><h5>Descuento</h5>
			<input name='descuento' type='text' placeholder='Ej: -25%' />
		</label>
	</div>";
}else if($fields["tipo"]=="curso"){
	$tipoOpcionVisible="Curso & Taller";
	$camposPropios=
	"
	<div class='small-6 columns'>
	<label><h5>Fecha</h5>
	<input name='fecha' type='date'/>
	</label>
	</div>
	<div class='small-6 columns'>
	<label><h5>Beneficio</h5>
	<input name='beneficio' type='text' placeholder='Ej: Beneficio' />
	</label>
	</div>";
	$camposPropios2="<div class='row text-left'>
			<div class='small-8 small-centered columns'>
			<div class='row'>
			<div class='small-6 columns'>
			<label><h5>Precio General</h5>
			<input name='precioNormal' type='text' placeholder='Ej: $25000' />
			</label>

			</div><div class='small-6 columns'>
			<label><h5>Precio Socio</h5>
			<input name='precioSocio' type='text' placeholder='Ej: $20000' />
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
			<input name='inscripciones' type='text' placeholder='Ej: Con Laura en la entrada' />
			</label>

			</div>
			</div>
			</div>
		</div>
		";
}else if($fields["tipo"]=="promocion"){
	$tipoOpcionVisible="Promoción";
	$camposPropios=
	"
	<div class='small-6 columns'>
	<label><h5>Vigencia</h5>
	<input name='vigencia' type='text' placeholder='Ej: Hasta el lunes' />
	</label>
	</div>
	<div class='small-6 columns'>
	<label><h5>Cantidad de Cupones</h5>
	<input name='cuponesDisponibles' type='text' placeholder='Ej: 100'/>
	</label>
	</div>
	";

}else if($fields["tipo"]=="social"){
	$tipoOpcionVisible="Social";
	$camposPropios=
	"<div class='small-12 columns'>
	<label><h5>Fecha</h5>
	<input name='fecha' type='date'/>
	</label>
	</div>
	";
}else if($fields["tipo"]=="panorama"){
	$tipoOpcionVisible="Panorama";
	$camposPropios=
	"<div class='small-6 columns'>
	<label><h5>Fecha</h5>
	<input name='fecha' type='date'/>
	</label>
	</div>
	<div class='small-6 columns'>
	<label><h5>Beneficio</h5>
	<input name='beneficio' type='text' placeholder='Ej: -25%'/>
	</label>
	</div>";
	$camposPropios2="<div class='row text-left'>
			<div class='small-8 small-centered columns'>
			<div class='row'>
			<div class='small-12 columns'>
			<label><h5>Precio Normal</h5>
			<input name='precioNormal' type='text' placeholder='Ej: $25000' />
			</label>

			</div>
			</div>
			</div>
		</div>
		
		";
}else if($fields["tipo"]=="concurso"){
	$tipoOpcionVisible="Concurso";
	$camposPropios=
	"<div class='small-6 columns'>
	<label><h5>Contacto</h5>
	<input name='contacto' type='text' placeholder='Ej: Jose Marin' />
	</label>
	</div>
	<div class='small-6 columns'>
	<label><h5>Fecha</h5>
	<input name='fecha' type='date'/>
	</label>
	</div>";
}

$urlPagina="../templates/editorCampos.html";
$fp                        = file_get_contents($urlPagina, "r");
$head                      = preg_replace("#(.*)<head>(.*?)</head>(.*)#is", '$2', $fp);
$fp                        = str_replace($head, "", $fp);
$obj_page                  = new page_class("header-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$enc = $obj_page->get_output();
$fp  = str_replace("<head></head>", $enc, $fp);
$obj_page                  = new page_class("footer-admin.html", "../templates/inc");
$obj_page->add_all("", "");
$fin = $obj_page->get_output();
$fp = str_replace('{tipoOpcion}', $tipoOpcionVisible, $fp);
$fp = str_replace('<div id="camposPropios"></div>', $camposPropios, $fp);
$fp = str_replace('<div id="camposPropios2"></div>', $camposPropios2, $fp);
$fp = str_replace('function cargarSelect(){', 'function cargarSelect(){' . getAllEmpresas($_SESSION["ciudad"]), $fp);
$fp = str_replace("</body>", $fin . '</body>', $fp);
$fp = str_replace("<title>PassClub</title>", "<title>Crear Cupon</title>", $fp);
$fields["contenidoPagina"] = $fp;
$obj_page = new page_class("administracion.html", "../templates");
$obj_page->add_all($fields, "");
$obj_page->display_output();
?>

