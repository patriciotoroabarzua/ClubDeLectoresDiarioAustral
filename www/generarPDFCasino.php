<?php 

include '../config/config.php';
if(isset($_SESSION['tipo'])){
	if(!descargaCasino()){

		header("location: index.php?ciudad=temuco");
	}else{

		$infoCasino=getCasinoInfo();
		$array=getCodigosCasino();
		if(isset($array)){
			$salida="";
			for($i=0;$i<count($array);$i++){
				$salida.="<li>".$array[$i]."</li>";
			}

			ob_start(); 
			?>
			<table style="width:600px;margin:20px 0 0 20px;border:1px solid #ddd;font-family:Arial,sans-serif;">
	<tr align="center">
		<td rowspan="2" style="border-bottom:1px solid #ddd;padding: 20px 0;">
			<img src="http://zonasur.passclub.cl/www/img/logo-1.png">
		</td>
		<td style="border-left:1px solid #ddd;"></td>
		</tr>
		<tr align="center">
			<td style="border-bottom:1px solid #ddd; border-left:1px solid #ddd; border-top:1px solid #ddd;padding: 10px 0;font-size: 14px;">v&aacute;lido:<br/>
				<strong><?php echo "Desde ".$infoCasino["desde"]." hasta ".$infoCasino["hasta"] ?></strong>
			</td>
		</tr>
		<tr align="left">
			<td colspan="2" style="padding:40px;font-size:14px;">
				<h3 style="font-size:60px;font-weight:bold;color:#0073E5; line-height: 0.9em; margin: 0px 0px 10px 0px; ">CASINO DREAMS TEMUCO</h3>
				<div style="line-height: 1em;">
					<p style="margin: 0px;">Accede GRATIS de lunes a domingo con estos códigos.<br>
					Beneficio exclusivo Club de Lectores de El Austral</p>
					<h3>Códigos</h3>
					<ul>
					<?php echo $salida ?>
					</ul>
				</div>	
			</td>
		</tr>
		<tr align="center">
			<td style="border-top: 1px solid #ddd;" colspan="2">
				<p style="text-transform:uppercase;margin-left:20px;padding-top: 5px;">V&Aacute;lido para <br/><strong><?php echo Ntildes($_SESSION["nombre"]) ?></strong></p>
			</td>
		</tr>
	</tr>
</table>
					<?php
	//DESCOMENTAR LA SIGUIENTE LINEA PARA PODER EDITAR
					//die;
					require_once("../clases/dompdf/dompdf_config.inc.php");
					$dompdf = new DOMPDF();
					$dompdf->load_html(ob_get_clean());
					$dompdf->render();
					$pdf = $dompdf->output();
					$filename = "casino".time().'.pdf';
					file_put_contents($filename, $pdf);
					$dompdf->stream($filename);
					unlink($filename);
					setHistorial("", $_SESSION["rut"], "casino");
					header("location: index.php?ciudad=temuco");
				}else{
					header("location: index.php?ciudad=temuco");
				}


			}
		}else{
			header("location: index.php?ciudad=temuco");
		}

		?>