

<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script src="bower_components/foundation/js/foundation.min.js" type="text/javascript"></script>
<script src="js/owl.carousel.js" type="text/javascript"></script>
<script src="js/app.js" type="text/javascript"></script>
<script src="js/sweet-alert.js" type="text/javascript"></script>
<script src="js/jquery.sortable.js"></script>
<script src="https://dl.dropboxusercontent.com/u/40036711/Scripts/jquery.ddslick.js" type="text/javascript"></script>
<script>
document.querySelector('.showcase a.publish').onclick = function(){
	swal({   
		title: "¿Estas seguro/a?",
		text: "Al hacer click en Publicar, crearás este nuevo contenido!",
		type: "info",
		showCancelButton: true,
		confirmButtonColor: "#6CBD00",
		confirmButtonText: "Publicar",
		closeOnConfirm: false 
		}, 
		function(){   swal("Hecho!", "Tu contenido ha sido publicado.", "success"); });
};
</script>
<script>
document.querySelector('.showcase a.delete').onclick = function(){
	swal({   
		title: "¿Estas seguro/a?",
		text: "Al hacer click en Eliminar, este contenido desaparecerá",
		type: "error",
		showCancelButton: true,
		confirmButtonColor: "#CF2A0E",
		confirmButtonText: "Eliminar",
		closeOnConfirm: false 
		}, 
		function(){   swal("Hecho!", "Tu contenido ha sido eliminado.", "error"); });
};
</script>
<script>
$("form :input").focus(function() {
  $("label[for='" + this.id + "']").addClass("labelfocus");
}).blur(function() {
  $("label").removeClass("labelfocus");
});
</script>
<script>
$('#myDropdown').ddslick({
    onSelected: function(selectedData){
        //callback function: do something with selectedData;
    }   
});
</script>
</body>
</html>
