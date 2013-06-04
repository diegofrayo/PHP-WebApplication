<?php

namespace modules\Periodo;

use modules\Asignatura\VistaAsignatura;

use modules\HelperModules;

use Dominio\Clases\Periodo;
require_once '/../Asignatura/VistaAsignatura.php';

class VistaPeriodo
{

	/**
	 * Datos es un array, con la informacion del usuario que se va a colocar en la plantilla
	 * @param unknown_type $datos
	 */
	public function imprimirHTML_Periodo($datos){

		/**
		 * 1 = Periodo
		 * 2 = Lista de asignaturas
		 */

		// 		echo "<div class='row-fluid'>".
		echo "<div id='divCarouselPeriodo' class='carousel slide'><div class='carousel-inner'>";
		$html= HelperModules::leerPlantillaHTML("Periodo","Periodo");

		//echo count($datos[2]);
		//Informacion del periodo
		$informacionPeriodoHTML = $this->crearInformacionPeriodo($datos[1]);
		$editarPeriodoHTML = $this->crearEditarPeriodo($datos[1]);
		$listaAsignaturasHTML = $this->crearListaDeAsignaturas($datos[2]);

		$html = str_replace("<!--{Informacion del periodo}-->", $informacionPeriodoHTML, $html);
		$html = str_replace("<!--{Editar Periodo}-->", $editarPeriodoHTML, $html);
		$html = str_replace("<!--{Lista de asignaturas}-->", $listaAsignaturasHTML, $html);
		echo $html;

		//Asignaturas
		$vistaAsignatura = new VistaAsignatura();
		$htmlAsignatura= "";
		$listaDeAsignaturas = $datos[2];
		$i = 0;
		foreach ($listaDeAsignaturas as $asignatura){
			$i= $i+1;
			$datosAsignatura =  array (1 => $asignatura,
					2=>$datos[1] , 3=>$i);
			$htmlAsignatura=$vistaAsignatura->imprimirHTML_Asignatura($datosAsignatura);
			echo $htmlAsignatura;

		}

		// 		echo "</div></div></div>";
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
				"</div>";
		return $html;
	}

	public function leerPlantillaHTML($nombreFile){
		return file_get_contents("html/".$nombreFile.".phtml", true);
	}

}
