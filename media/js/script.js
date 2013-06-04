$(document).ready(function() {

	// Muevo los mensajes de error y exito
	$("#divNavMensajes").append($('.divTextoMensajesExito'));
	$("#divNavMensajes").append($('.divTextoMensajesError'));

	// Muevo los botones de los periodos
	$("#divNavButtons").append($('#divNavButtonsPHP'));

	// Inicio el carousel de periodos
	$('.carousel').carousel();
	$('.carousel').carousel('pause');

	// Instancias calendarios
	$(".inputCalendars").datepicker();
	$('.inputCalendars').datepicker('option', {
		dateFormat : 'yy/mm/dd'
	});

	// Inicio collapse
	// $(".collapse").collapse();
	// $(".collapseHidden").collapse('hide');

	// Validar formularios
	$("#formRegistro").validate();
	$("#formEditarAsignatura").validate();
	$("#formCrearNota").validate();
	$("#formCrearPeriodo").validate();
	$("#formEditarPeriodo").validate();
	$("#formCrearAsignatura").validate();

});

// function ajax(idElemento, filePHP) {
function ajax(filePHP, idElemento, nombreAccion) {

	var parametros = {
		action : nombreAccion,
		ajax : "ajax"
	};

	$.ajax({
		data : parametros,
		url : filePHP,
		type : 'post',
		beforeSend : function() {
			$(idElemento).html("Procesando, espere por favor...");
		},
		success : function(response) {
			$(idElemento).html(response);
		}
	});

}

function desplazarCarousel(indice) {

	for ( var int = 0; int < indice; int++) {
		setTimeout('moverCarousel()', 1000);
		alert("probando");
		$('.carousel').carousel('next');
	}
}

function moverCarousel() {
	$('.carousel').carousel('next');
}