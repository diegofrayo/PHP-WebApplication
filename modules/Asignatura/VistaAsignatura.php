<?php

namespace modules\Asignatura;

use modules\HelperModules;

use Dominio\Clases\Asignatura;

class VistaAsignatura
{

	/**
	 * Datos es un array, con la informacion del usuario que se va a colocar en la plantilla
	 * @param unknown_type $datos
	 */
	public function imprimirHTML_Asignatura($datos){

		/**
		 *1 = Asignatura
		 *2= Periodo Padre
		 *3= Numero asignatura (indice)
		 */

		$html= HelperModules::leerPlantillaHTML("Asignatura","Asignatura");

		$informacionAsignaturaHTML = $this->crearInformacionAsignatura($datos[1]);
		$editarAsignaturaHTML = $this->crearEditarAsignatura($datos[1] , "");
		//$selectGrupoDeNotasHTML = $this->crearSelectGrupoNotas($listaDeGrupoDeNotas);

		$html = str_replace("<!--{Informacion de la asignatura}-->", $informacionAsignaturaHTML, $html);
		$html = str_replace("<!--{Editar Asignatura}-->", $editarAsignaturaHTML, $html);
		$html = str_replace("<!--{Indice Asignatura}-->", $datos[3], $html);
		//	$html = str_replace("<!-- Select Grupo Notas -->", $selectGrupoDeNotasHTML, $html);

		echo $html;
	}

	private function crearInformacionAsignatura(Asignatura $asignatura)
	{
		$html="<table class='tablaInformacion'>".
				"<tbody><tr><td>Nombre</td>".
				"<td>".$asignatura->getNombre()."</td>".
				"</tr><tr><td>Numero de notas principales</td>".
				"<td>".$asignatura->getNumeroDeNotas()."</td>".
				"</tr><tr><td>Periodo</td>".
				"<td>".$asignatura->getPeriodo()->getNombre()."</td>".
				"</tr><tr><td>Nota Final</td>".
				"<td>".$asignatura->getNotaFinal()."</td>".
				"</tr></tbody></table>";
		return $html;
	}

	private function crearEditarAsignatura(Asignatura $asignatura, $listaDePeriodos)
	{
		$html = "<label>Nombre</label><div>".
				"<input name='nombre' type='text' maxlength='20' value = '".$asignatura->getNombre()."' required />".
				"</div><label>Numero de notas </label><div>".
				"<input name='notas' type='number' maxlength='2' value = '".$asignatura->getNumeroDeNotas()."'required />".
				"</div><label>Nota final </label><div>".
				"<input name='notaFinal' type='number' value = '".$asignatura->getNotaFinal()."' required />".
				"</div><label>Periodo </label><div>";

		// 		$htmlPeriodos = "<select name = 'periodo'>";
		// 		foreach ($listaDePeriodos as $periodo){
		// 			$option = "<option value= '".$periodo->getId()."'>".$periodo->getNombre()."</option>";
		// 			$htmlPeriodos.=$option;
		// 		}
		// 		$htmlPeriodos = "</select>";
		$htmlPeriodos = "";
		$html.=	$htmlPeriodos."</div><input type='hidden' name='idAsignatura' value='".$asignatura->getId()."' />";
		return $html;
	}

	private function crearSelectGrupoNotas($listaDeGrupoDeNotas)
	{
		$htmlGrupoNotas = "<select name = 'grupo'>";
		foreach ($listaDeGrupoDeNotas as $grupo){
			$option = "<option value= '".$grupo->getId()."'>".$grupo->getNombre()."</option>";
			$htmlGrupoNotas.=$option;
		}
		$htmlGrupoNotas = "</select>";
		return $htmlGrupoNotas;
	}

}
