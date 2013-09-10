//Estas variables globales se utilizan para borrar y editar notas
var idNota = 0;
var filaSeleccionada = "";
var tablaSeleccionada = "";
var rootSite = '/Qualify';
var notaSeleccionada;
// var rootSite = 'http://qualify.hol.es';

function Nota(id, nombre, valor) {
	this.nombre = nombre;
	this.id = id;
	this.valor = valor;
	this.porcentaje = 0;
	this.fecha = "";
}

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

	// Validar formularios
	$("#formRegistro").validate();
	$("#formEditarAsignatura").validate();
	$("#formCrearNota").validate();
	$("#formCrearPeriodo").validate();
	$("#formEditarPeriodo").validate();
	$("#formCrearAsignatura").validate();

	// Desactivar el boton de registro
	document.getElementById("submitRegistro").disabled = true;

	// Desactivar el autocompletado
	$(":text").attr("autocomplete", "off");
	$(":email").attr("autocomplete", "off");
	$(":number").attr("autocomplete", "off");
	$(":date").attr("autocomplete", "off");

	$('#divModalEditarNota').modal('hide');

});

function desplazarCarousel(indice) {
	$('.carousel').carousel(indice);
	$('.carousel').carousel('pause');
}

function modificarTituloApp(titulo) {
	document.title = titulo + " - Qualify";
}

function ajax(filaPHP, idElementoSalida, parametros, funcionRespuesta) {

	$.ajax({
		data : parametros,
		url : filaPHP,
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

	ajax(rootSite + '/modules/Periodo/ControladorPeriodo.php',
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

		ajax(rootSite + '/modules/Home/ControladorHome.php',
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

		ajax(rootSite + '/modules/Home/ControladorHome.php',
				'#divEmailDisponible', parametros, funcion);

	} else {
		var div = $('#divEmailDisponible');
		div.css("color", "red");
		div.html("Ingrese un email");
	}
}

function dialogBorrarNota(botonPresionado, idDeLaNota) {
	idNota = idDeLaNota;
	tablaSeleccionada = botonPresionado.parentNode.parentNode.parentNode;
	filaSeleccionada = botonPresionado.parentNode.parentNode;
}

function borrarNota() {

	var parametrosMetodo = {
		action : 'Borrar Nota',
		ajax : "ajax",
		id : idNota,
	};

	var funcion = function(idElementoSalida, response) {

		$(idElementoSalida).html(response);
		$("#divNavMensajes").append($('.divTextoMensajesExito'));
		$("#divNavMensajes").append($('.divTextoMensajesError'));

		tablaSeleccionada.removeChild(filaSeleccionada);

		$('#divModalBorrarNota').modal('hide');

		idNota = 0;
		filaSeleccionada = "";
		tablaSeleccionada = "";

	};

	ajax(rootSite + '/modules/Asignatura/ControladorAsignatura.php',
			'#divNavMensajes', parametrosMetodo, funcion);

}

function dialogBorrarGrupo(idGrupoNotas, indiceAsignatura) {
	document.formBorrarGrupo.idGrupo.value = idGrupoNotas;
	document.formBorrarGrupo.indiceAsignatura.value = indiceAsignatura;
	$('#divModalBorrarGrupo').modal('show');
}

function borrarGrupo() {
	document.formBorrarGrupo.submit();
	$('#divModalBorrarGrupo').modal('hide');
}

function dialogBorrarAsignatura(idAsignaturaNota) {
	document.formBorrarAsignatura.idAsignatura.value = idAsignaturaNota;
}

function borrarAsignatura() {
	document.formBorrarAsignatura.submit();
	$('#divModalBorrarAsignatura').modal('hide');
}

function calcularPromedioGrupo(tablaNotasHTML, divSalidaPromedio,
		opcionPorcentajesIguales) {

	var tablaNotas = $(tablaNotasHTML + ' tbody tr');
	var promedio = 0;

	if (opcionPorcentajesIguales == 1) {
		var sumatoriaNotas = 0;
		var numeroDeNotas = 0;
		tablaNotas.each(function(index) {
			$(this).children("td").each(function(index2) {
				switch (index2) {
				case 1:
					sumatoriaNotas += parseFloat($(this).text());
					numeroDeNotas++;
					break;
				}
			});
		});
		promedio = sumatoriaNotas / numeroDeNotas;
	} else {
		var notaActual = 0;
		var porcentajeActual = 0;
		tablaNotas.each(function(index) {
			$(this).children("td").each(function(index2) {
				switch (index2) {
				case 1:
					notaActual = parseFloat($(this).text());
					break;
				case 2:
					porcentajeActual = parseFloat($(this).text());
					break;
				}
			});
			promedio += ((notaActual / 100) * porcentajeActual);
		});
	}
	$(divSalidaPromedio).html("El promedio es de: " + promedio);
}

function cerrarSesion() {
	document.formCerrarSesion.submit();
}

function dialogEditarNota(botonPresionado, idDeLaNota) {

	tablaSeleccionada = botonPresionado.parentNode.parentNode.parentNode;
	filaSeleccionada = botonPresionado.parentNode.parentNode.cells;
	var numeroColumnas = filaSeleccionada.length;

	notaSeleccionada = new Nota(idDeLaNota, filaSeleccionada[0].innerHTML,
			filaSeleccionada[1].innerHTML);

	// Hay porcentaje
	if (numeroColumnas == 6) {
		notaSeleccionada.porcentaje = filaSeleccionada[2].innerHTML;
		notaSeleccionada.fecha = filaSeleccionada[3].innerHTML;
	} else {
		notaSeleccionada.porcentaje = 0;
		notaSeleccionada.fecha = filaSeleccionada[2].innerHTML;
		document.getElementById('labelPorcentaje').innerHTML = "No requiere porcentaje";
	}

	document.formEditarNota.idNota.value = idDeLaNota;
	document.formEditarNota.nombre.value = notaSeleccionada.nombre;
	document.formEditarNota.valor.value = notaSeleccionada.valor;
	document.formEditarNota.porcentaje.value = notaSeleccionada.porcentaje;
	document.formEditarNota.fecha.value = notaSeleccionada.fecha;

}

function editarNota() {
	var form = $("#formEditarNota").validate();
	form.form();
	var isValido = form.valid();
	if (isValido == true) {
		document.formEditarNota.submit();
		$('#divModalEditarNota').modal('hide');
	}
}