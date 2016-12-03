var customIcons = {
    veterinaria: {
	   icon: 'img/ico/veterinaria.png'
    },
    turismo: {
	    icon: 'img/ico/turismo.png'
    },
    salud: {
	    icon: 'img/ico/salud.png'
    },
    restaurant: {
	   icon: 'img/ico/restaurant.png'
    },
    pub: {
	    icon: 'img/ico/pub.png'
    },
    casino: {
	    icon: 'img/ico/casino.png'
    },
    infantil: {
	   icon: 'img/ico/infantil.png'
    },
    discoteque: {
	    icon: 'img/ico/discoteque.png'
    },
    deporte: {
	    icon: 'img/ico/deporte.png'
    },
    comidarapida: {
	   icon: 'img/ico/comida-rapida.png'
    },
    comercio: {
	    icon: 'img/ico/comercio.png'
    },
    casino: {
	    icon: 'img/ico/casino.png'
    },
    cafeteria: {
	    icon: 'img/ico/cafeteria.png'
    },
    belleza: {
	   icon: 'img/ico/belleza.png'
    },
    automotriz: {
	    icon: 'img/ico/automotriz.png'
    },
    default: {
	    icon: 'img/ico/default.png'
    },
    cine: {
	    icon: 'img/ico/cine.png'
    }
};

function load(empresas, zoomMap) {
	var latitudCentral=0;
	var longitudCentral=0;
	
	//alert("hola");
	if(zoomMap==7){
		latitudCentral= -39.858840;
		longitudCentral= -72.799214;
	}else if(zoomMap==16){
		latitudCentral= empresas[0]["latitud"];
		longitudCentral= empresas[0]["longitud"];
	}else{
		if(empresas[0]["ciudad"].toLowerCase()=="temuco"){
			latitudCentral= -38.7372296;
			longitudCentral= -72.6136927;
		}else if(empresas[0]["ciudad"].toLowerCase()=="valdivia"){
			latitudCentral= -39.8268637;
			longitudCentral= -73.2361773;
		}else if(empresas[0]["ciudad"].toLowerCase()=="osorno"){
			latitudCentral= -40.5772711;
			longitudCentral= -73.1327388;
		}else if(empresas[0]["ciudad"].toLowerCase()=="puerto montt"){
			latitudCentral= -41.4698621;
			longitudCentral= -72.9413987;
		}
	}
	var map = new google.maps.Map(document.getElementById("map"), {
		center: new google.maps.LatLng(latitudCentral, longitudCentral),
		zoom: zoomMap,
		mapTypeId: 'roadmap',
		scrollwheel:false,
		scaleControl:false,
		panControl:true,
		mapTypeControl:false,
		streetViewControl:false,
		overviewMapControl:false,
		zoomControl: true
	});
	//console.log(map);
	for(var i=0;i<empresas.length;i++){
	//for(var i=0;i<10;i++){
		var infoWindow = new google.maps.InfoWindow;
		if(empresas[i]["logo"]!="img/"){
			var logo = "<img src='"+empresas[i]["logo"]+"' width='100px' height='100px'><br/><b>" ;
			}else{
				var logo="";
			}
		var name = empresas[i]["nombre"];
		var address = empresas[i]["direccion"];
		var type = (empresas[i]["tipo"].toLowerCase()).replace("comida rapida","comidarapida");
		var point = new google.maps.LatLng(
			parseFloat(empresas[i]["latitud"]),
			parseFloat(empresas[i]["longitud"]));
		var html = logo + name + "</b> <br/>" + address;
		var icon = customIcons[type] || customIcons["default"];
		var marker = new google.maps.Marker({
			map: map,
			position: point,
			icon: icon.icon
		});
		bindInfoWindow(marker, map, infoWindow, html);
	}
}

function bindInfoWindow(marker, map, infoWindow, html) {
	google.maps.event.addListener(marker, 'click', function() {
		infoWindow.setContent(html);
		infoWindow.open(map, marker);
	});
}
function loadFiltro(empresas, zoomMap, filtro) {
	var latitudCentral=0;
	var longitudCentral=0;
	if(zoomMap==7){
		latitudCentral= -39.858840;
		longitudCentral= -72.799214;
	}else{
		if(empresas[0]["ciudad"].toLowerCase()=="temuco"){
			latitudCentral= -38.7372296;
			longitudCentral= -72.6136927;
		}else if(empresas[0]["ciudad"].toLowerCase()=="valdivia"){
			latitudCentral= -39.8268637;
			longitudCentral= -73.2361773;
		}else if(empresas[0]["ciudad"].toLowerCase()=="osorno"){
			latitudCentral= -40.5772711;
			longitudCentral= -73.1327388;
		}else if(empresas[0]["ciudad"].toLowerCase()=="puerto montt"){
			latitudCentral= -41.4698621;
			longitudCentral= -72.9413987;
		}
	}
	var map = new google.maps.Map(document.getElementById("map"), {
		center: new google.maps.LatLng(latitudCentral, longitudCentral),
		zoom: zoomMap,
		mapTypeId: 'roadmap',
		scrollwheel:false,
		scaleControl:false,
		panControl:true,
		mapTypeControl:false,
		streetViewControl:false,
		overviewMapControl:false,
		zoomControl: true
	});
	var aux=false;
	//console.log(filtro);
	for(var i=0;i<empresas.length;i++){
		for(var j=0;j<filtro.length;j++){
			
			if(empresas[i]["tipo"].toLowerCase()==filtro[j]){

				aux=true;
				break;
			}
		}
		if(aux){
			var infoWindow = new google.maps.InfoWindow;
			if(empresas[i]["logo"]!="img/"){
			var logo = "<img src='"+empresas[i]["logo"]+"' width='100px' height='100px'><br/><b>" ;
			}else{
				var logo="";
			}
			var name = empresas[i]["nombre"];
			var address = empresas[i]["direccion"];
			var type = (empresas[i]["tipo"].toLowerCase()).replace("comida rapida","comidarapida");
			var point = new google.maps.LatLng(
				parseFloat(empresas[i]["latitud"]),
				parseFloat(empresas[i]["longitud"]));
			var html = logo + name + "</b> <br/>" + address;
			var icon = customIcons[type] || {};
			var marker = new google.maps.Marker({
				map: map,
				position: point,
				icon: icon.icon
			});
			bindInfoWindow(marker, map, infoWindow, html);
			aux=false;
		}
	}
}