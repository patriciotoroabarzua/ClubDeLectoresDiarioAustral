<?php
include_once("../clases/page_class.php");
include '../funciones/funciones.php';
session_start();
$_MENSAJEINTERNO;
$_IDCUPONCREADO;
if(isset($_SESSION['tipo'])){
	if($_SESSION['tipo']=="admin"){
		$fields["login"]="
		<a href='gestorCupones.php' class='button small radius secondary'><span class='entypoicon'>&#9881;</span> Administración</a>
		<a href='logout.php' class='button small radius secondary'><span class='entypoicon'>&#59201;</span> Cerrar sesión</a>
		";

	}else if($_SESSION['tipo']=="usuario"){
		$fields["login"]="
		<a href='visorPerfilUsuario.php' class='button small radius secondary'><span class='entypoicon'>&#9881;</span> Perfil</a>
		<a href='logout.php' class='button small radius secondary'><span class='entypoicon'>&#59201;</span> Cerrar sesión</a>
		";
	}
}else{
	$fields["login"]="
			<a role='button' aria-label='submit form' href='login.php' class='button small success radius'><span class='entypoicon'>&#128100;</span> Ingresar</a>
		";
	
}
?>