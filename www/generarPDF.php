<?php 

include '../config/config.php';
if(isset($_SESSION['tipo'])){
	if(isset($_GET["cuponid"])){
		$cantidadDescargasUsuario=getCantidadDescargaCupon($_GET["cuponid"], $_SESSION["rut"]);
		if($cantidadDescargasUsuario<2){
			$array=getCupon($_GET["cuponid"]);
			if(isset($array["tipoOpcion"])){
				$fields=$array;
				$fields["imagen"]=$array["imagen"][0];
				ob_start(); 
				?>
				<table style="width:600px;margin:20px 0 0 20px;border:1px solid #ddd;font-family:Arial,sans-serif;">
					<tr align="center">
						<td rowspan="2" style="border-bottom:1px solid #ddd;padding: 20px 0;">
							<img src="http://zonasur.passclub.cl/www/img/logo-1.png"></td>
							<td style="font-size:60px;font-weight:bold;color:#0073E5;border-left:1px solid #ddd;"><?php echo $fields["auxiliar"] ?></td>
						</tr>
						<tr align="center">
							<td style="border-bottom:1px solid #ddd; border-left:1px solid #ddd; border-top:1px solid #ddd;padding: 10px 0;font-size: 14px;">v&aacute;lido:<br/>
								<strong><?php echo $fields["vigencia"] ?></strong></td>
							</tr>
							<tr align="left">
								<td colspan="2" style="padding:40px;font-size:14px;line-height:20px;">
									<h1 style="border-bottom:1px solid #ddd;padding-bottom:20px;">
										<span style="color:#0073E5;line-height:50px">Empresa</span>
										<br/><?php echo $fields["nombre"] ?></h1>
										<h2>Descripci&oacute;n</h2>
										<p><?php echo Ntildes($fields["descripcion"]); ?></p>
										<p style="color:#0073E5;">Tarifa v&aacute;lida <strong><?php echo $fields["vigencia"] ?></strong></p>
									</td>
								</tr>
								<tr align="center">
									<td style="border-top: 1px solid #ddd;"><p style="text-transform:uppercase;margin-left:20px;padding-top: 5px;">V&Aacute;lido para <br/><strong><?php echo Ntildes($_SESSION["nombre"]) ?></strong></p></td>
									<td style="background:#ddd;border-top: 1px solid #ddd;"><h3>Cupón N°<?php echo ((int)$array["cuponesDisponibles"]) - getCantidadHistorial($_GET["cuponid"]); ?></h3></td>
								</tr>
							</table>
							<?php
//echo $_SESSION["nombre"];die;
							require_once("../clases/dompdf/dompdf_config.inc.php");
							$dompdf = new DOMPDF();
							$dompdf->load_html(ob_get_clean());
							$dompdf->render();
							$pdf = $dompdf->output();
							$filename = "cupon".time().'.pdf';
							file_put_contents($filename, $pdf);
							$dompdf->stream($filename);
							unlink($filename);
							setHistorial($_GET["cuponid"], $_SESSION["rut"], "usar");
						}
					}else{
						header("location: visor.php?cuponid=".$_GET["cuponid"]);
					}
				}
			}else{
				header("location: index.php");
			}
			?>