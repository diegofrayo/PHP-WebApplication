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
	//$(".collapse").collapse();

	// Validar formularios
	$("#formRegistro").validate();
	$("#formEditarAsignatura").validate();
	$("#formCrearNota").validate();
	$("#formCrearPeriodo").validate();
	$("#formEditarPeriodo").validate();
	$("#formCrearAsignatura").validate();

});

// El parametro a veces se va a utilizar
function ajax(filePHP, idElemento, nombreAccion, id) {

	var parametros = {
		action : nombreAccion,
		ajax : "ajax",
		id : id,

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
	$('.carousel').carousel(indice);
	$('.carousel').carousel('pause');
}

function modificarTituloApp(titulo) {
	document.title = "@" + titulo + " - Qualify";
}
