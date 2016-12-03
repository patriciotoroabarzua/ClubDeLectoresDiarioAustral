<?php
include '../config/config.php';
alertaMaxima();
if(!isset($_GET["cuponid"])){
	header("Location: index.php");
}
$array=getCupon($_GET["cuponid"]);
if(!isset($array)){
	header("Location: index.php");
}
if(isset($array["tipoOpcion"])){
	$fields["id"]=$array["id"];
	$fields["titulo"]=Ntildes($array["titulo"]);
	$fields["descripcion"]=Ntildes($array["descripcion"]);
	$fields["lugar"]=$array["lugar"];
	$fields["telefono"]=$array["telefono"];
	$fields["tipoOpcion"]=$array["tipoOpcion"];
	$fields["imagen"]=$array["imagen"];
	$fields["logo"]=$array["logo"];
	$fields["ciudad"]=$array["ciudad"];
	$fields["direccion"]=$array["direccion"];
	$fields["web"]=$array["web"];
	$fields["facebook"]=$array["facebook"];
	$fields["auxiliar"]=$array["auxiliar"];
	$fields["imagen"]=$array["imagen"][0];
	$blocks["relacionados"]=getRelacionados($array["id"],$array["tipoOpcion"]);
	$fields["infoTipo"]="";
	if($fields["tipoOpcion"]=="beneficio"){
		if($array["categoria"]!=""){
			$fields["infoTipo"].="
			<tr>
			<td>Precio Referencial</td>
			<td>".$array["categoria"]."</td>
			</tr>";
		}
		if($array["descuento"]!=""){
			$fields["infoTipo"].="
			<tr>
			<td>Precio Referencial</td>
			<td>".$array["descuento"]."</td>
			</tr>";
		}
	}else if($fields["tipoOpcion"]=="curso"){

		if($array["fecha"]!=""){
			$fields["infoTipo"].="
			<tr>
			<td>Fecha</td>
			<td>".$array["fecha"]."</td>
			</tr>";
		}
		if($array["precioNormal"]!=""){
			$fields["infoTipo"].="
			<tr>
			<td>Precio General</td>
			<td>".$array["precioNormal"]."</td>
			</tr>";
		}
		if($array["precioSocio"]!=""){
			$fields["infoTipo"].="
			<tr>
			<td>Precio Socio</td>
			<td>".$array["precioSocio"]."</td>
			</tr>";
		}
		if($array["beneficio"]!=""){
			$fields["infoTipo"].="
			<tr>
			<td>Beneficio</td>
			<td>".$array["beneficio"]."</td>
			</tr>";
		}
		if($array["inscripciones"]!=""){
			$fields["infoTipo"].="
			<tr>
			<td>Inscripciones</td>
			<td>".$array["inscripciones"]."</td>
			</tr>
			";
		}
	}else if($fields["tipoOpcion"]=="promocion"){
		$restanNum = ((int)$array["cuponesDisponibles"]) - getCantidadHistorial($_GET["cuponid"]);
		$cantidadDescargasUsuario=getCantidadDescargaCupon($_GET["cuponid"], $_SESSION["rut"]);
		if($cantidadDescargasUsuario<2){
			if($restanNum>0){
				$restan ='<span class="label radius">Quedan '.$restanNum.' descargas</span>';
			}else{
				$fields["auxiliar"]="<small>AGOTADO</small>";
				$restan='<span class="label radius">Quedan 0 descargas</span>';
			}
		}else{
			$restanNum=0;
			$restan='<span class="label radius">Alcanzado limite por usuario</span>';
		}
		if($array["vigencia"]!=""){
			$fields["infoTipo"].="
			<tr>
			<td>Vigencia</td>
			<td>".$array["vigencia"]."</td>
			</tr>";
		}
		if($array["cuponesDisponibles"]!=""){
			$fields["infoTipo"].="
			<tr>
			<td>Cupones</td>
			<td>".$array["cuponesDisponibles"]."</td>
			</tr>
			<tr>";
		}

		
	}else if($fields["tipoOpcion"]=="social"){
		header("Location: visorSocial.php?cuponid=".$_GET["cuponid"]);
	}else if($fields["tipoOpcion"]=="panorama"){
		if($array["fecha"]!=""){
			$fields["infoTipo"].="
			<tr>
			<td>Fecha</td>
			<td>".$array["fecha"]."</td>
			</tr>";
		}
		if($array["beneficio"]!=""){
			$fields["infoTipo"].="
			<tr>
			<td>Beneficio</td>
			<td>".$array["beneficio"]."</td>
			</tr>";
		}
		if($array["precioNormal"]!=""){
			$fields["infoTipo"].="
			<tr>
			<td>Precio Normal</td>
			<td>".$array["precioNormal"]."</td>
			</tr>
			";
		}
	}else if($fields["tipoOpcion"]=="concurso"){
		if($array["fecha"]!=""){
			$fields["infoTipo"].="
			<tr>
			<td>Fecha</td>
			<td>".$array["fecha"]."</td>
			</tr>";
		}
		if($array["contacto"]!=""){

			$fields["infoTipo"].="
			<tr>
			<td>Contacto</td>
			<td>".$array["contacto"]."</td>
			</tr>
			";
		}
	}
}
$tipoOpcion=$fields["tipoOpcion"];
if($tipoOpcion=="curso"){
	$fields["tipoOpcionTitulo"]="Cursos & Talleres";
	$fields["colorTitulo"]="cursos";
}else if($tipoOpcion=="promocion"){
	$fields["tipoOpcionTitulo"]= "PromoClub";
	$fields["colorTitulo"]= "promoclub";

}else if($tipoOpcion=="social"){
	$fields["tipoOpcionTitulo"]= "Sociales";
	$fields["colorTitulo"]= "sociales";

}else{
	$fields["tipoOpcionTitulo"]=ucwords($tipoOpcion);
	$fields["colorTitulo"]= $tipoOpcion."s";

}
if(isset($_SESSION['tipo'])){
	if($_SESSION['tipo']=="admin"){
		$fields["btnEliminar"]='<li><a href="borrar.php?cuponid='.$_GET["cuponid"].'&tipoOpcion='.$fields["tipoOpcion"].'" class="button tiny alert">Eliminar</a></li>
		<li><a href="editarCupon.php?cuponid='.$_GET["cuponid"].'&tipoOpcion='.$fields["tipoOpcion"].'" class="button tiny">Editar</a></li>
		';
	}
	if($fields["tipoOpcion"]=="promocion"){
		if($restanNum>0){
			$fields["voucher"]='<a href="generarPDF.php?cuponid='.$fields["id"].'" class="button success radius button-voucher">
			Descargar cupón '.$restan.'
			</a>';
		}else{
			$fields["voucher"]='<a href="#" class="button alert radius button-voucher" disabled>Descargar cupón 
			'.$restan.'
			</a>';
		}
	}
}
$empresas=getEmpresa($array["Empresaid"]);

if($fields["direccion"]!=""){
	$fields["direccion"]="<td>Dirección</td>
	<td>".$fields["direccion"]."</td>";
}
if($fields["lugar"]!=""){
	$fields["lugar"]="<td>Lugar</td>
	<td>".$fields["lugar"]."</td>";
}
if($fields["telefono"]!=""){
	$fields["telefono"]="<td>Telefono</td>
	<td>".$fields["telefono"]."</td>";
}
if($fields["web"]!=""){
	$fields["web"]="<td>Sitio Web</td>
	<td><a href='".$fields["web"]."' target='_blank'>".$fields["web"]."</a></td>";
}
if($fields["facebook"]!=""){
	$fields["facebook"]="<td>Facebook</td>
	<td><a href='http://facebook.com/".$fields["facebook"]."' target='_blank'>".$fields["facebook"]."</a></td>";
}


if(isset($empresas)){
	$fields["empresas"] = $empresas;
}
/*
if(isset($_SESSION["tipo"])){
	setHistorial($_GET["cuponid"], $_SESSION["rut"], "ver");
}else{
	setHistorial($_GET["cuponid"], "invitado", "ver");
}*/
$urlPagina="../templates/administracion_cuerpo.html";
$fp                        = file_get_contents($urlPagina, "r");
$head                      = preg_replace("#(.*)<head>(.*?)</head>(.*)#is", '$2', $fp);
$fp                        = str_replace($head, "", $fp);

$obj_page                  = new page_class("visor.html", "../templates/");
$obj_page->add_all($fields, $blocks);
$index = $obj_page->get_output();
$fp  = str_replace("<body>", "<body>".$index, $fp);

$obj_page                  = new page_class("searchlog.html", "../templates/inc");
$obj_page->add_all($fields, "");
$searchlog = $obj_page->get_output();
$fp  = str_replace("<body>", "<body>".$searchlog, $fp);

$obj_page                  = new page_class("header-index.html", "../templates/inc");
$obj_page->add_all($fields, "");
$header = $obj_page->get_output();
$fp  = str_replace("</head><body>", $header, $fp);

$obj_page                  = new page_class("footer-index.html", "../templates/inc");
$obj_page->add_all($fields, "");
$fin = $obj_page->get_output();
$fp = str_replace("</body>", $fin . '</body>', $fp);
$fp = str_replace("<title>PassClub</title>", "<title>".$fields["tipoOpcionTitulo"]." en PassClub</title>", $fp);
$fp = str_replace("<td>NULL</td>", "<td>---</td>", $fp);
$fieldsHtml["contenidoPagina"] = $fp;
$obj_page = new page_class("administracion.html", "../templates");
$obj_page->add_all($fieldsHtml, "");
$obj_page->display_output();
?>