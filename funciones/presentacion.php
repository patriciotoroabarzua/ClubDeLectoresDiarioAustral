<?php 
function ordenarArrayAll($arrayCup){
	$salida="";
	for($i = 0; $i<count($arrayCup);$i++){
		if($arrayCup[$i]["tipoOpcion"]=="social"){
			$salida.="<li class='mod-cupones-item'>
			<a href='visor.php?cuponid=".$arrayCup[$i]["id"]."'>
			<article>
			<div class='row collapse' data-equalizer>
			<div class='small-3 columns mod-figure' data-equalizer-watch>
			<figure>
			<img src='".$arrayCup[$i]["logo"]."'>
			</figure>
			</div>
			<div class='small-9 columns' data-equalizer-watch>
			<header>
			<div class='ciudad'>".$arrayCup[$i]["ciudad"]."</div>
			<h3>".$arrayCup[$i]["titulo"]."</h3>
			<div class='direccion'>".$arrayCup[$i]["direccion"]."</div>
			</header>
			</div>
			</div>
			</article>
			</a>
			</li>";
		}else{
			$salida.="<li class='mod-cupones-item'>
			<a href='visor.php?cuponid=".$arrayCup[$i]["id"]."'>
			<article>
			<div class='row collapse' data-equalizer>
			<div class='small-3 columns mod-figure' data-equalizer-watch>
			<figure>
			<img src='".$arrayCup[$i]["logo"]."'>
			</figure>
			<div class='descuento'>".$arrayCup[$i]["auxiliar"]."</div>
			</div>
			<div class='small-9 columns' data-equalizer-watch>
			<header>
			<div class='ciudad'>".$arrayCup[$i]["ciudad"]."</div>
			<h3>".$arrayCup[$i]["titulo"]."</h3>
			<div class='direccion'>".$arrayCup[$i]["direccion"]."</div>
			</header>
			</div>
			</div>
			</article>
			</a>
			</li>";
		}
	}
	
	return $salida;
}

function ordenarRevista($arrayCup){
	if(isset($arrayCup)){
		$salida="<div class='large-6 medium-6 small-12 columns post-cupon-content' data-equalizer-watch>
		<div class='row post-revista'>
		<div class='large-6 medium-6 small-12 columns'>
		<div data-configid='".$arrayCup[0]["revistaCodigo"]."' style='width: 100%; height: 300px;' class='issuuembed'></div>
		<script type='text/javascript' src='//e.issuu.com/embed.js' async='true'></script>
		</div>
		<div class='large-6 medium-6 small-12 columns'>
		<header>
		<h3>Revista <strong>PassClub <span class='ciudad primary-color'>".ucwords($arrayCup[0]["ciudad"])."</span></strong></h3>
		</header>
		<div class='content'>
		<p><strong>Números Anteriores:</strong></p>
		<ul>";
		for($i = 1; $i<count($arrayCup);$i++){
			if($i<6){
				$salida.="<li><a href='http://issuu.com/passclub/docs/".$arrayCup[$i]["revista"]."' target='_blank'>".$arrayCup[$i]["titulo"]."</a></li>";			
			}
		}
		$salida.="</ul>
		<a href='#' class='button tiny radius more-button'>ver nº actual</a>
		</div>
		</div>
		</div>
		</div>";	
		return $salida;
	}
}
function mostrarSlide($arrayCup){
	$salida="";
	for($i = 0; $i<6;$i++){
		if($i<count($arrayCup)){
			$salida.="<li id='Item".($i+1)."' value='".$arrayCup[$i]["id"]."' class='habilitado exclude-item'>
			<input class='dd-option-value' type='hidden' value='0'> 
			<img class='dd-option-image' src='".$arrayCup[$i]["imagen"]."'> 
			<label class='dd-option-text'>".$arrayCup[$i]["titulo"]."</label> 
			<small class='dd-option-description dd-desc'>".$arrayCup[$i]["auxiliar"]."</small>
			<button onclick='eliminarList(".($i+1).")' class='exclude-btn'>X</button>
			</li>";
		}else{
			$salida.="<li id='Item".($i+1)."' class='disabled exclude-item'>
			<label class='dd-option-text'>Vacio</label> 
			</li>";
		}
		
	}
	return $salida;
}
function ordenarSlideSelect($arrayCup){
	$salida="<option value=''></option>";
	for($i = 0; $i<count($arrayCup);$i++){
		$salida.='	<option value="'.$arrayCup[$i]["id"].'" data-imagesrc="'.$arrayCup[$i]["imagen"].'" data-aux="'.$arrayCup[$i]["auxiliar"].'">'.$arrayCup[$i]["titulo"].'</option>';
	}

	return $salida;
}
function ordenarPublicidad($pub){
	$salida="<figure class='ads-mod'>
	<a href='".$pub["url"]."' target='_blank'>
	<img src='".$pub["publicidad"]."'>
	</a>
	</figure>";

	return $salida;

}
function sliderSocial($arrayCup){
	$salida="";
	for($i = 0; $i<count($arrayCup);$i++){
		$salida.="<div class='item'>
		<img src='".$arrayCup[$i]."'></div>";
	}
	return $salida;

}

?>