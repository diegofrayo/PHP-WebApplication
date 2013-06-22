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

		//Matriz de notas
		$listaDeGruposDeNotas = $dtoAsignatura->getMatrizListaDeNotasDeUnGrupo();

		//Lista de grupos
		$listaDeGrupos = $dtoAsignatura->getListaDeGrupos();
		$i = 0;
		foreach ($listaDeGruposDeNotas as $grupo){
			echo $this->crearGrupoDeNotas($grupo, $listaDeGrupos[$i]);
			$i = $i + 1;
		}

		echo "</div></div></div>";

		//Por ultimo imprimo un modal oculto
		echo $this->crearModalParaBorrarAsignatura($dtoAsignatura->getAsignatura()->getId());
	}

	private function crearInformacionAsignatura(Asignatura $asignatura)
	{
		$html="<table class='tablaInformacion'>".
				"<tbody><tr><td>Nombre:</td>".
				"<td>".$asignatura->getNombre()."</td></tr>".
				"<tr><td>Periodo:</td>".
				"<td>".$asignatura->getPeriodo()->getNombre()."</td></tr>".
				"<tr><td>Eliminar Asignatura:</td>".
				"<td><a href='#divModalBorrarAsignatura' role='button' data-toggle='modal'>".
				"<span id='button-remove' class='sprite'></span></a>".
				"</td></tr></tbody></table>";
		return $html;
	}

	private function crearEditarAsignatura(Asignatura $asignatura, $listaDePeriodos)
	{
		$html = "<label>Nombre</label><div>".
				"<input name='nombre' type='text' maxlength='20' value = '".$asignatura->getNombre()."' required />".
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

	private function crearGrupoDeNotas($listaDeNotas , $grupo)
	{
		$html = "<div class='row-fluid'><div class='span12'><div class='moduloApp'>";

		//Creo el titulo
		$html .= "<div class='divTituloModulo'>".
				"<h1><a data-toggle='collapse' href='#collapseGrupo".$grupo->getId()."'".
				" class='linkCollapse'>".$grupo->getNombre().
				"<i class='icon-chevron-down'></i></a></h1></div>";

		//Creo el contenido
		$html.="<div class='collapse' id='collapseGrupo".$grupo->getId()."'><div class='row-fluid'>";

		if(count($listaDeNotas)){

			//Creo la tabla de notas (columna1)
			$html.=	"<div class='span6'>";

			$html.=	"<table class='table tablaNotas'>".
					"<thead><tr><th>Nombre</th>".
					"<th>Valor</th><th>Porcentaje</th>".
					"<th>Fecha</th><th>Editar</th>".
					"<th>Borrar</th></tr></thead><tbody>";

			foreach ($listaDeNotas as $nota){
				$html.=
				"<tr><td>".$nota->getNombre()."</td>".
				"<td>".$nota->getValor()."</td>".
				"<td>".$nota->getPorcentaje()."</td>".
				"<td>".$nota->getFecha()."</td>".
				"<td><a href='#divModalEditarNota' role='button'".
				" data-toggle='modal'".
				" onclick='eventoEditarNota(this, ".$nota->getId().")'>".
				"<span id='button-editar' class='sprite'></span></a></td>".
				"<td><a ".
				" onclick='borrarNota(this,".$nota->getId().")'><span".
				" id='button-remove' class='sprite'></span> </a></td>".
				"</tr>";
			}
			//Cierra la columna 1
			$html.="</tbody></table></div>";

			//Creo la info del grupo (columna 2)
			$html.=	"<div class='span6'>";

			//Cierra la columna 2
			$html.="</div>";

		}else{

			$html.=	"<div class='span6'></div><div class='span6'></div>";

		}

		//Cierra el contenido
		$html.="</div></div>";

		//Cierro los divs padres
		$html.="</div></div></div>";

		return $html;
	}

	private function crearModalParaBorrarAsignatura($idAsignatura){
		$html = "<div id='divModalBorrarAsignatura' class='modal hide fade' tabindex='-1'".
				"role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>".
				"<div class='modal-header'>".
				"<button type='button' class='close' data-dismiss='modal'".
				"aria-hidden='true'>x</button><h3 id='myModalLabel'>Esta seguro</h3>".
				"</div>	<div class='modal-body'>".
				"<p>Borrar&iacute;a la asignatura, con todas sus notas</p>".
				"</div>	<div class='modal-footer'>".
				"<form class='form-inline'".
				" enctype='multipart/form-data' method='post'".
				" action='/../modules/Asignatura/ControladorAsignatura.php'>".
				"<input type='hidden' value='".$idAsignatura."' name='idAsignatura'> <input".
				" class='btn btn-primary' type='submit' name='action'".
				" value='Borrar Asignatura' />".
				"<button class='btn' data-dismiss='modal' aria-hidden='true'>".
				"Cancelar</button></form></div></div>";
		return $html;
	}

}
