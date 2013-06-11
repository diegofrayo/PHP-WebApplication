<?php

namespace modules\Asignatura;

use Dominio\DTO\DTOModuloAsignatura;

use modules\HelperModules;

use Dominio\Clases\Asignatura;

class VistaAsignatura
{

	public function imprimirHTML_Asignatura(DTOModuloAsignatura $dtoAsignatura){

		echo "<div class='item'><div class='row-fluid'><div class='span12'>";

		$html= HelperModules::leerPlantillaHTML("Asignatura","Asignatura");

		//Imprime la parte inicial de la asignatura
		$informacionAsignaturaHTML = $this->crearInformacionAsignatura($dtoAsignatura->getAsignatura());
		$editarAsignaturaHTML = $this->crearEditarAsignatura($dtoAsignatura->getAsignatura() , $dtoAsignatura->getListaDePeriodosDeUnUsuario());
		$formCrearNota = $this->crearFormCrearNota($dtoAsignatura->getListaDeGrupos(), $dtoAsignatura->getAsignatura());

		$html = str_replace("<!--{Informacion de la asignatura}-->", $informacionAsignaturaHTML, $html);
		$html = str_replace("<!--{Editar Asignatura}-->", $editarAsignaturaHTML, $html);
		$html = str_replace("<!--{Indice Asignatura}-->", $dtoAsignatura->getIndice(), $html);
		$html = str_replace("<!--{Crear Nota}-->", $formCrearNota, $html);

		echo $html;

		//Imprime los grupos de la asignatura



		echo "</div></div></div>";
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
				"</div><label>Periodo </label>";

		$htmlPeriodos = "<div><select name = 'periodo'>";
		foreach ($listaDePeriodos as $periodo){
			$option = "<option value= '".$periodo->getId()."'>".$periodo->getNombre()."</option>";
			$htmlPeriodos.=$option;
		}
		$htmlPeriodos.= "</select></div>";
		$html.=	$htmlPeriodos."<input type='hidden' name='idAsignatura' value='".$asignatura->getId()."' />";
		return $html;
	}

	private function crearSelectGrupoNotas($listaDeGruposDeNotas)
	{
		$htmlGruposNotas = "<select name = 'grupo'>";
		foreach ($listaDeGruposDeNotas as $grupo){
			$option = "<option value= '".$grupo->getId()."'>".$grupo->getNombre()."</option>";
			$htmlGruposNotas.=$option;
		}
		$htmlGruposNotas .= "</select>";
		return $htmlGruposNotas;
	}

	private function crearFormCrearNota($listaDeGruposDeNotas , $asignatura)
	{
		$html = "<label>Nombre</label><div>".
				"<input name='nombre' type='text' maxlength='10' required />".
				"</div><label>Valor </label><div>".
				"<input name='valor' type='number' required maxlength='4' />".
				"</div><label>Porcentaje </label><div>".
				"<input name='porcentaje' type='number' maxlength='3' required />".
				"</div><label>Grupo </label><div>";

		$html.= $this->crearSelectGrupoNotas($listaDeGruposDeNotas);

		$html .="</div><label>Fecha </label><div>".
				"<input type='text' class='inputCalendars' name='fecha' required />".
				"</div><input type='hidden' name='idAsignatura'".
				"value='".$asignatura->getId()."' />".
				"<input class='btn btn-primary' type='submit' name='action' value='Crear Nota' />";

		return $html;
	}

	private function crearGrupoDeNotas()
	{
		$html = "<div class='row-fluid'>";
		$html.=	"<div class='span6'>";

		$html.="</div>";

		$html.=	"<div class='span6'>";

		$html.="</div>";

		$html.="</div>";

		return $html;
	}

}
