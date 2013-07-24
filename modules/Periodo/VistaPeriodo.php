<?php

namespace modules\Periodo;

use Dominio\DTO\DTOModuloPeriodo;
use modules\Asignatura\VistaAsignatura;
use modules\HelperModules;
use Dominio\Clases\Periodo;

$_SERVER['DOCUMENT_ROOT'] = 'C:/xampp/htdocs/Qualify';

require_once $_SERVER['DOCUMENT_ROOT'].'/modules/Asignatura/VistaAsignatura.php';

class VistaPeriodo
{

	public function imprimirHTML_Periodo(DTOModuloPeriodo $dtoPeriodo, $arrayDTOAsignatura){

		echo "<div id='divCarouselPeriodo' class='carousel slide'><div class='carousel-inner'>";
		$html= HelperModules::leerPlantillaHTML("Periodo","Periodo");
		$listaDeAsignaturas = $dtoPeriodo->getListaAsignaturasDeUnPeriodo();

		//Informacion del periodo
		$informacionPeriodoHTML = $this->crearInformacionPeriodo($dtoPeriodo->getPeriodo());
		$editarPeriodoHTML = $this->crearEditarPeriodo($dtoPeriodo->getPeriodo());
		$listaAsignaturasHTML = $this->crearListaDeAsignaturas($listaDeAsignaturas);

		$html = str_replace("<!--{Informacion del periodo}-->", $informacionPeriodoHTML, $html);
		$html = str_replace("<!--{Editar Periodo}-->", $editarPeriodoHTML, $html);
		$html = str_replace("<!--{Lista de asignaturas}-->", $listaAsignaturasHTML, $html);
		$html = str_replace("<!--{Id Periodo}-->", $dtoPeriodo->getPeriodo()->getId(), $html);
		$html = str_replace("<!--{Root Site}-->", HelperModules::$ROOT_SITE, $html);


		if(count($listaDeAsignaturas)){
			$html = str_replace("<!--{Navegacion Buttons}-->", $this->crearButtonsNavegacion(), $html);
		}
		echo $html;

		//Asignaturas
		$vistaAsignatura = new VistaAsignatura();
		$htmlAsignatura= "";

		foreach ($arrayDTOAsignatura as $dtoAsignatura){
			$htmlAsignatura=$vistaAsignatura->imprimirHTML_Asignatura($dtoAsignatura);
			echo $htmlAsignatura;
		}

		echo "</div></div>";

		//Por ultimo imprimo varios modales ocultos
		echo $this->crearModalParaBorrarPeriodo($dtoPeriodo->getPeriodo()->getId());
		echo $vistaAsignatura->crearModalParaBorrarAsignatura();
		echo $vistaAsignatura->crearModalParaBorrarNota();
		echo $vistaAsignatura->crearModalParaBorrarGrupo();
		//echo $this->crearModalParaEditarNota();

	}

	private function crearListaDeAsignaturas($listaDeAsignaturas){
		$html = "<ul>";
		$i = 1;
		foreach ($listaDeAsignaturas as $asignatura){
			$itemAsignatura = "<li><a href='javascript:void(0)' onclick='desplazarCarousel(".$i.");'>".$asignatura->getNombre()."</a></li>";
			$html.=$itemAsignatura;
			$i= $i+1;
		}
		$html.= "</ul>";
		return $html;
	}

	private function crearInformacionPeriodo(Periodo $periodo){
		$html="<table class='tablaInformacion'>".
				"<tbody><tr><td>Nombre:</td>".
				"<td>".$periodo->getNombre()."</td>".
				"</tr><tr><td>Fecha de inicio:</td>".
				"<td>".$periodo->getFechaInicio()."</td>".
				"</tr><tr><td>Fecha de finalizaci&oacute;n:</td>".
				"<td>".$periodo->getFechaFinal()."</td>".
				"</tr><tr><td>Eliminar:</td>".
				"<td><a href='#divModalBorrarPeriodo' role='button' data-toggle='modal'>".
				"<span id='button-remove' class='sprite'></span> </a>".
				"</td></tr></tbody></table>";

		return $html;
	}

	private function crearEditarPeriodo(Periodo $periodo)
	{

		$html = "<label>Nombre</label><div>".
				"<input name='nombre' type='text' maxlength='15' value = '".$periodo->getNombre()."' required />".
				"</div><label>Fecha de inicio </label><div>".
				"<input type='text' class='inputCalendars' value = '".
				"' name='fechaInicio' required />".
				"</div><label>Fecha de finalizacion </label><div>".
				"<input type='text' class='inputCalendars' value = '".
				"' name='fechaFinal' required />".
				"</div>"."<input type='hidden' name='id' value='".$periodo->getId()."' />";
		return $html;
	}

	private function crearButtonsNavegacion(){
		$html = "<div id='divNavButtonsPHP'>
				<a href='javascript:void(0)' onclick='desplazarCarousel(0);'> <span id='button-home-periodo'".
				" class='sprite'></span></a><a href='#divCarouselPeriodo' data-slide='prev'> <span id='button-left'".
				" class='sprite'></span></a> <a href='#divCarouselPeriodo' data-slide='next'><span".
				" id='button-right' class='sprite'></span> </a></div>";
		return $html;
	}

	private function crearModalParaBorrarPeriodo($idPeriodo){
		$html = "<div id='divModalBorrarPeriodo' class='modal hide fade' tabindex='-1'".
				" role='dialog' aria-hidden='true'>".
				"<div class='modal-header'>".
				"<button type='button' class='close' data-dismiss='modal'".
				" aria-hidden='true'>x</button><h3 id='myModalLabel'>Esta seguro</h3>".
				"</div>	<div class='modal-body'>".
				"<p>Borrar&iacute;a el periodo, con todas sus asignaturas y	notas</p>".
				"</div>	<div class='modal-footer'>".
				"<form class='form-inline'".
				" enctype='multipart/form-data' method='post'".
				" action='<!--{Root Site}-->/modules/Periodo/ControladorPeriodo.php'>".
				"<input type='hidden' value='".$idPeriodo."' name='id'> <input".
				" class='btn btn-primary' type='submit' name='action'".
				" value='Borrar Periodo' />".
				"<button class='btn' data-dismiss='modal' aria-hidden='true'>".
				"Cancelar</button></form></div></div>";
		return $html;
	}

}
