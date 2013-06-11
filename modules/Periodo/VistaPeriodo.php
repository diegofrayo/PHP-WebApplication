<?php

namespace modules\Periodo;

use Dominio\DTO\DTOModuloPeriodo;

use modules\Asignatura\VistaAsignatura;

use modules\HelperModules;

use Dominio\Clases\Periodo;
require_once '/../Asignatura/VistaAsignatura.php';

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
				"<tbody><tr><td>Nombre</td>".
				"<td>".$periodo->getNombre()."</td>".
				"</tr><tr><td>Descripci&oacute;n</td>".
				"<td>".$periodo->getDescripcion()."</td>".
				"</tr><tr><td>Fecha de inicio</td>".
				"<td>".$periodo->getFechaInicio()."</td>".
				"</tr><tr><td>Fecha de finalizaci&oacute;n</td>".
				"<td>".$periodo->getFechaFinal()."</td>".
				"</tr></tbody></table>";
		return $html;
	}

	private function crearEditarPeriodo(Periodo $periodo){
		$html = "<label>Nombre</label><div>".
				"<input name='nombre' type='text' maxlength='15' value = '".$periodo->getNombre()."' required />".
				"</div><label>Fecha de inicio </label><div>".
				"<input type='text' class='inputCalendars' value = '".$periodo->getFechaInicio().
				"' name='fechaInicio' required />".
				"</div><label>Fecha de finalizacion </label><div>".
				"<input type='text' class='inputCalendars' value = '".$periodo->getFechaFinal().
				"' name='fechaFinal' required />".
				"</div><label>Descripci&oacute;n </label><div>".
				"<textarea maxlength='30' name='descripcion'>".$periodo->getDescripcion()."</textarea>".
				"<input type='hidden' name='id' value='".$periodo->getId()."' /></div>";
		return $html;
	}

	private function crearButtonsNavegacion(){
		$html = "<div id='divNavButtonsPHP'>
				<a href='javascript:void(0)' onclick='desplazarCarousel(0);'> <span id='button-home-periodo'".
				"class='sprite'></span></a><a href='#divCarouselPeriodo' data-slide='prev'> <span id='button-left'".
				"class='sprite'></span></a> <a href='#divCarouselPeriodo' data-slide='next'><span".
				" id='button-right' class='sprite'></span> </a></div>";
		return $html;
	}

}
