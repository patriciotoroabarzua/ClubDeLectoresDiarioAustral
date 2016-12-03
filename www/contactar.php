<?php  
include '../config/config.php';
extract($_POST);
if($ciudad=="puerto montt"){
 $destino="clubdelectores@diariollanquihue.cl";
}else if($ciudad=="valdivia"){
$destino="clubdelectores@australvaldivia.cl";
}else if($ciudad=="osorno"){
$destino="clubdelectores@austraosorno.cl";
}else if($ciudad=="temuco"){
$destino="clubdelectores@australtemuco.cl";
}
$mensajeX="Nombre: ".$nombre."\n";
$mensajeX.="Rut: ".$rut."\n";
$mensajeX.="Ciudad: ".$ciudad."\n";
$mensajeX.="Mail: ".$mail."\n";
$mensajeX.="Socio: ".$socio."\n";
$mensajeX.="Motivo: ".$motivo."\n";
$mensajeX.="Mensaje: ".$mensaje."\n";

mail($destino, $motivo, $mensajeX);

$_SESSION["mensajeTipo"]="exito";
$_SESSION["mensajeSalida"]= "Mensaje enviado";
header("Location: index.php");
?>