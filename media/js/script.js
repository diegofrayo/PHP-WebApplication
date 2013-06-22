$(document).ready(function() {

	// Muevo los mensajes de error y exito
	$("#divNavMensajes").append($('.divTextoMensajesExito'));
	$("#divNavMensajes").append($('.divTextoMensajesError'));

	// Muevo los botones de los periodos
	$("#divNavButtons").append($('#divNavButtonsPHP'));

	// Inicio el carousel de periodos
	$('.carousel').carousel();
	$('.carousel').carousel('pause');

	$('.inputCalendars').datepicker({
		format : 'yyyy-mm-dd'
	});

	// Inicio collapse
	// $(".collapse").collapse();

	// Validar formularios
	$("#formRegistro").validate();
	$("#formEditarAsignatura").validate();
	$("#formCrearNota").validate();
	$("#formCrearPeriodo").validate();
	$("#formEditarPeriodo").validate();
	$("#formCrearAsignatura").validate();

	// Desactivar el boton de registro
	document.getElementById("submitRegistro").disabled = true;

});

function desplazarCarousel(indice) {
	$('.carousel').carousel(indice);
	$('.carousel').carousel('pause');
}

function modificarTituloApp(titulo) {
	document.title = "@" + titulo + " - Qualify";
}

function ajax(filePHP, idElementoSalida, parametros, funcionRespuesta) {

	$.ajax({
		data : parametros,
		url : filePHP,
		type : 'post',
		beforeSend : function() {
			$(idElementoSalida).html("Procesando, espere por favor...");
		},
		success : function(response) {
			funcionRespuesta(idElementoSalida, response);
		}
	});

}

function calcularPromedioPeriodo(idPeriodo) {

	var parametrosMetodo = {
		action : 'Calcular Promedio',
		ajax : "ajax",
		id : idPeriodo,
	};

	var funcion = function(idElementoSalida, response) {
		$(idElementoSalida).html(response);
	};

	ajax('http://ProjectPHP/modules/Periodo/ControladorPeriodo.php',
			'#divPromedioPeriodo', parametrosMetodo, funcion);
}

function comprobarNickDisponible() {

	var nick = document.getElementById("nickRegistro").value;

	if (nick != '') {

		var parametros = {
			action : 'Comprobar Nick',
			ajax : "ajax",
			nick : nick
		};

		var funcion = function(idElementoSalida, response) {

			$(idElementoSalida).html(response);

			var div = $('#divNickDisponible');

			if (div.html() == 'Disponible') {
				document.getElementById("submitRegistro").disabled = false;
				div.css("color", "green");
			} else {
				document.getElementById("submitRegistro").disabled = true;
				div.css("color", "red");
			}
		};

		ajax('http://ProjectPHP/modules/Home/ControladorHome.php',
				'#divNickDisponible', parametros, funcion);

	} else {
		var div = $('#divNickDisponible');
		div.css("color", "red");
		div.html("Ingrese un nick");
	}
}

function comprobarEmailDisponible() {

	var email = document.getElementById("emailRegistro").value;

	if (email != '') {

		var parametros = {
			action : 'Comprobar Email',
			ajax : "ajax",
			email : email
		};

		var funcion = function(idElementoSalida, response) {

			$(idElementoSalida).html(response);

			var div = $('#divEmailDisponible');

			if (div.html() == 'Disponible') {
				document.getElementById("submitRegistro").disabled = false;
				div.css("color", "green");
			} else {
				document.getElementById("submitRegistro").disabled = true;
				div.css("color", "red");
			}
		};

		ajax('http://ProjectPHP/modules/Home/ControladorHome.php',
				'#divEmailDisponible', parametros, funcion);

	} else {
		var div = $('#divEmailDisponible');
		div.css("color", "red");
		div.html("Ingrese un email");
	}
}

function borrarNota(botonPresionado, idNota) {

	var parametrosMetodo = {
		action : 'Borrar Nota',
		ajax : "ajax",
		id : idNota,
	};

	var funcion = function(idElementoSalida, response) {

		$(idElementoSalida).html(response);
		$("#divNavMensajes").append($('.divTextoMensajesExito'));
		$("#divNavMensajes").append($('.divTextoMensajesError'));

		var tabla = botonPresionado.parentNode.parentNode.parentNode;

		tabla.removeChild(botonPresionado.parentNode.parentNode);
		// alert("Nota borrada correctamente");
	};

	ajax('http://ProjectPHP/modules/Asignatura/ControladorAsignatura.php',
			'#divNavMensajes', parametrosMetodo, funcion);

}
