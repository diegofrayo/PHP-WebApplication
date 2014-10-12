<?php

namespace modules\asignatura;

use domain\DTO\DTOModuloAsignatura;
use modules\HelperModules;
use domain\classes\Asignatura;

class VistaAsignatura
{
	public function imprimirHTML_Asignatura(DTOModuloAsignatura $dtoAsignatura)
	{

		echo "<div class='item'><div class='row-fluid'><div class='span12'>";

		$html= HelperModules::leerPlantillaHTML("asignatura","Asignatura");

		//Imprime la parte inicial de la asignatura
		$indiceAsignatura = $dtoAsignatura->getIndice();
		$informacionAsignaturaHTML = $this->crearInformacionAsignatura($dtoAsignatura->getAsignatura());
		$editarAsignaturaHTML = $this->crearEditarAsignatura($dtoAsignatura->getAsignatura() , $dtoAsignatura->getListaDePeriodosDeUnUsuario(), $indiceAsignatura);
		$formCrearNota = $this->crearFormCrearNota($dtoAsignatura->getListaDeGrupos(), $dtoAsignatura->getAsignatura(), $indiceAsignatura);

		$html = str_replace("<!--{Informacion de la asignatura}-->", $informacionAsignaturaHTML, $html);
		$html = str_replace("<!--{Editar Asignatura}-->", $editarAsignaturaHTML, $html);
		$html = str_replace("<!--{Indice Asignatura}-->",$indiceAsignatura , $html);
		$html = str_replace("<!--{Crear Nota}-->", $formCrearNota, $html);
		$html = str_replace("<!--{Id Asignatura}-->", $dtoAsignatura->getAsignatura()->getId(), $html);
		$html = str_replace("<!--{Root Site}-->", HelperModules::$ROOT_SITE, $html);

		echo $html;

		//Imprime los grupos de la asignatura

		//Matriz de notas
		$listaDeGruposDeNotas = $dtoAsignatura->getMatrizListaDeNotasDeUnGrupo();

		//Lista de grupos
		$listaDeGrupos = $dtoAsignatura->getListaDeGrupos();
		$i = 0;
		
		foreach ($listaDeGruposDeNotas as $grupo){
			
			echo $this->crearGrupoDeNotas($grupo, $listaDeGrupos[$i], $indiceAsignatura);
			$i = $i + 1;
		}

		echo "</div></div></div>";
	}

	private function crearInformacionAsignatura(Asignatura $asignatura)
	{
		$html="<table class='tablaInformacion' table-layout:fixed>".
				"<tbody><tr><td>Nombre:</td>".
				"<td>".$asignatura->getNombre()."</td></tr>".
				"<tr><td>Periodo:</td>".
				"<td>".$asignatura->getPeriodo()->getNombre()."</td></tr>".
				"<tr><td>Eliminar:</td>".
				"<td><a onclick='dialogBorrarAsignatura(".$asignatura->getId().")' href='#divModalBorrarAsignatura' role='button' data-toggle='modal'>".
				"<span id='button-remove' class='sprite'></span></a>".
				"</td></tr></tbody></table>";

		$botonBorrar = "<a class='btn btn-danger' >Borrar Grupo</a>";
		return $html;
	}

	private function crearEditarAsignatura(Asignatura $asignatura, $listaDePeriodos, $indiceAsignatura)
	{
		$html = "<label>Nombre</label><div>".
				"<input name='nombre' type='text' maxlength='30' value = '".$asignatura->getNombre()."' required />".
				"</div><label>Periodo </label>";

		$htmlPeriodos = "<div><select name = 'periodo'>";
		
		$idPeriodoAsignatura = $asignatura->getPeriodo()->getId();
		foreach ($listaDePeriodos as $periodo){
			
            if ($idPeriodoAsignatura == $periodo->getId()) {
                
                $option = "<option value= '" . $idPeriodoAsignatura . "' selected>" .
                         $periodo->getNombre() . "</option>";
            } else {
                
                $option = "<option value= '" . $periodo->getId() . "'>" .
                         $periodo->getNombre() . "</option>";
            }
            
            $htmlPeriodos .= $option;
		}
		
		$htmlPeriodos.= "</select></div>";
		$html.=	$htmlPeriodos."<input type='hidden' name='idAsignatura' value='".$asignatura->getId()."' />";
		$html.= "<input type='hidden' name='indiceAsignatura' value='".$indiceAsignatura."' />";
		
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

	private function crearFormCrearNota($listaDeGruposDeNotas , $asignatura, $indiceAsignatura)
	{
		$html = "<label>Nombre</label><div>".
				"<input name='nombre' type='text' maxlength='25' required />".
				"</div><label>Valor </label><div>".
				"<input name='valor' type='number' required maxlength='4' step='any' />".
				"</div><label>Porcentaje </label><div>".
				"<input name='porcentaje' type='number' maxlength='3' required />".
				"</div><label>Grupo </label><div>";

		$html.= $this->crearSelectGrupoNotas($listaDeGruposDeNotas);

		$html .="</div><label>Fecha </label><div>".
				"<input type='text' class='inputCalendars' name='fecha' required />".
				"</div><input type='hidden' name='idAsignatura'".
				" value='".$asignatura->getId()."' />".
				"<input type='hidden' name='indiceAsignatura' value='".$indiceAsignatura."' />".
				"<div class='divButtonSubmitCenter'><input class='btn btn-primary' type='submit' name='action' value='Crear Nota' /></div>";

		return $html;
	}

	private function crearGrupoDeNotas($listaDeNotas , $grupo, $indiceAsignatura)
	{

		$botonBorrar = "";

		$aux =  "";
		$tituloModulo = "";

		//Si es grupo defecto
		if($grupo->getEsGrupoDefecto() == true){
			$aux ="<div class='row-fluid'><div class='span12'><div class='moduloApp divGrupoDefecto'>";
			$tituloModulo = $grupo->getId().". Calificaciones";
		}else{
			$aux = "<div class='row-fluid'><div class='span12'><div class='moduloApp'>";
			$tituloModulo =  $grupo->getId().". ".$grupo->getNombre();
		}

		$html = $aux;

		//Creo el titulo
		$html .= "<div class='divTituloModulo'>".
				"<h1><a data-toggle='collapse' href='#collapseGrupo".$grupo->getId()."'".
				" class='linkCollapse'>".$tituloModulo.
				"<i class='icon-chevron-down'></i></a></h1></div>";

		//Creo el contenido
		$html.="<div class='collapse' id='collapseGrupo".$grupo->getId()."'><div class='row-fluid'>";

		//Si el grupo tiene notas
		if(count($listaDeNotas)>0){

			//Creo la tabla de notas (columna1)
			$html.=	"<div class='span6'>";

			if($grupo->getPorcentajesIguales() ==true){

				$html.=	"<div class='table-responsive'><table class='table tablaNotas' id='tablaNotas".$grupo->getId()."' >".
						"<thead><tr><th>Nombre</th>".
						"<th>Valor</th>".
						"<th>Fecha</th>".
						"<th>Editar</th>".
						"<th>Borrar</th></tr></thead><tbody id ='".$grupo->getId()."'>";

				foreach ($listaDeNotas as $nota){
					$html.=
					"<tr><td>".$nota->getNombre()."</td>".
					"<td>".$nota->getValor()."</td>".
					"<td>".$nota->getFecha()."</td>".

					"<td><a href='#divModalEditarNota' role='button'".
					" data-toggle='modal'".
					" onclick='dialogEditarNota(this, ".$nota->getId().")'>".
					"<span id='button-editar' class='sprite'></span></a></td>".

					"<td><a ".
					" onclick='dialogBorrarNota(this,".$nota->getId().")' href='#divModalBorrarNota' role='button' data-toggle='modal'><span".
					" id='button-remove' class='sprite'></span> </a></td>".
					"</tr>";
				}
			}else{

				$html.=	"<div class='table-responsive'><table class='table tablaNotas' id='tablaNotas".$grupo->getId()."' >".
						"<thead><tr><th>Nombre</th>".
						"<th>Valor</th><th>Porcentaje</th>".
						"<th>Fecha</th>".
						"<th>Editar</th>".
						"<th>Borrar</th></tr></thead><tbody id ='".$grupo->getId()."' >";

				foreach ($listaDeNotas as $nota){
					$html.=
					"<tr><td>".$nota->getNombre()."</td>".
					"<td>".$nota->getValor()."</td>".
					"<td>".$nota->getPorcentaje()."</td>".
					"<td>".$nota->getFecha()."</td>".

					"<td><a href='#divModalEditarNota' role='button'".
					" data-toggle='modal'".
					" onclick='dialogEditarNota(this, ".$nota->getId().")'>".
					"<span id='button-editar' class='sprite'></span></a></td>".

					"<td><a ".
					" onclick='dialogBorrarNota(this,".$nota->getId().")' href='#divModalBorrarNota' role='button' data-toggle='modal'><span".
					" id='button-remove' class='sprite'></span> </a></td>".
					"</tr>";
				}
			}

			$html.="</tbody></table></div><br/>";

			$html.="<table class='tablaPromedio' style='text-align: center; width: 98%;'>".
					"<tbody><tr><td style='width:50%;'>".
					"<a class='btn' onclick='calcularPromedioGrupo(&#39;#tablaNotas".$grupo->getId()."&#39; ,&#39;#divPromedioGrupo".$grupo->getId()."&#39;, ".$grupo->getPorcentajesIguales().");'>".
					"Calcular Promedio </a></td>".
					"<td style='width:50%;'><div id='divPromedioGrupo".$grupo->getId()."'></div></td>".
					"</tr></tbody></table>";

			//Cierra la columna 1
			$html.="</div>";

		}else{

			$html.=	"<div class='span6'></div>";

		}

		//Creo la info del grupo (columna 2)
		$html.=	"<div class='span6'>";

		if($grupo->getEsGrupoDefecto() == true){

			$html.=	"<form name='formEditarGrupo' enctype='multipart/form-data'".
					" method='post' action='".HelperModules::$ROOT_SITE."/modules/asignatura/ControladorAsignatura.php'>".
					"<div class='divFormularios moduloApp'>".
					"<div class='descripcionFormularios'>".
					"<h1>Configuraci&oacute;n</h1>".
					"</div><div class='divInputsFormularios'>".
					"<label style='display:inline;'>Porcentajes Iguales</label>";

			if($grupo->getPorcentajesIguales() ==true){
				$html.=	"<input type='checkbox' id='checkEditarGrupo' name='porcentajesIguales' style='display:inline;' checked />";
			}else{
				$html.=	"<input type='checkbox' id='checkEditarGrupo' name='porcentajesIguales' style='display:inline;' />";
			}

			$html.=	"<input type='hidden' value='".$grupo->getEsGrupoDefecto()."' name='grupo_defecto' />".
					"<input type='hidden' value='".$grupo->getId()."' name='idGrupo' />".
					"<input type='hidden' name='indiceAsignatura' value='".$indiceAsignatura."' />".
					"<input type='submit' class='btn btn-primary' name='action'".
					" value='Editar Grupo' />";

			$html.="</div></div></form>";

		}else{

			$html.=	"<form name='formEditarGrupo' enctype='multipart/form-data'".
					" method='post' action='".HelperModules::$ROOT_SITE."/modules/asignatura/ControladorAsignatura.php'>".
					"<div class='divFormularios moduloApp'>".
					"<div class='descripcionFormularios'>".
					"<h1>Configuraci&oacute;n</h1>".
					"</div><div class='divInputsFormularios'>".
					"<label>Nombre </label><div>".
					"<input name='nombre' id='nombreEditarGrupo' type='text' maxlength='25' value='".$grupo->getNombre()."' required />".
					"</div><div class='divButtonSubmitCenter'><label style='display:inline;'>Porcentajes Iguales</label>";

			if($grupo->getPorcentajesIguales() ==true){
				$html.=	"<input type='checkbox' id='checkEditarGrupo' name='porcentajesIguales' style='display:inline;' checked />";
			}else{
				$html.=	"<input type='checkbox' id='checkEditarGrupo' name='porcentajesIguales' style='display:inline;' />";
			}

			$html.=	"</div><input type='hidden' value='".$grupo->getEsGrupoDefecto()."' name='grupo_defecto' />".
					"<input type='hidden' value='".$grupo->getId()."' name='idGrupo' />".
					"<input type='hidden' name='indiceAsignatura' value='".$indiceAsignatura."' />".
					"<div class='divButtonSubmitCenter'><input type='submit' class='btn btn-primary' name='action'".
					" value='Editar Grupo' />";

			$html.="<a class='btn btn-danger' onclick='dialogBorrarGrupo(".$grupo->getId().",".$indiceAsignatura.")'>Borrar Grupo</a></div>";

			$html.="</div></div></form>";

		}

		//Cierra la columna 2
		$html.="</div>";

		//Cierra el contenido
		$html.="</div></div>";

		//Cierro los divs padres
		$html.="</div></div></div>";

		return $html;
	}

	public function crearModalParaBorrarAsignatura()
	{
		$html = "<div id='divModalBorrarAsignatura' class='modal hide fade' tabindex='-1'".
				" role='dialog'  aria-hidden='true'>".
				"<div class='modal-header'>".
				"<button type='button' class='close' data-dismiss='modal'".
				" aria-hidden='true'>x</button><h3 id='myModalLabel'>Esta seguro</h3>".
				"</div>	<div class='modal-body'>".
				"<form name='formBorrarAsignatura' id='formBorrarAsignatura' enctype='multipart/form-data'".
				" method='post' action='".HelperModules::$ROOT_SITE."/modules/asignatura/ControladorAsignatura.php'>".
				"<p>Borrar&iacute;a la asignatura, con todas sus notas</p>".
				"<input type='hidden' name='idAsignatura' />".
				"<input type='hidden' name='action' value='Borrar Asignatura' />".
				"</form></div><div class='modal-footer'>".
				"<button class='btn btn-primary' onclick='borrarAsignatura()' >".
				"Borrar</button>".
				"<button class='btn' data-dismiss='modal' aria-hidden='true'>".
				"Cancelar</button></div></div>";

		return $html;
	}

	public function crearModalParaBorrarGrupo()
	{
		$html = "<div id='divModalBorrarGrupo' class='modal hide fade' tabindex='-1'".
				" role='dialog'  aria-hidden='true'>".
				"<div class='modal-header'>".
				"<button type='button' class='close' data-dismiss='modal'".
				" aria-hidden='true'>x</button><h3 id='myModalLabel'>Esta seguro</h3>".
				"</div><div class='modal-body'>".
				"<form name='formBorrarGrupo' id='formBorrarGrupo' enctype='multipart/form-data'".
				" method='post' action='".HelperModules::$ROOT_SITE."/modules/asignatura/ControladorAsignatura.php'>".
				"<p>Borrar&iacute;a el grupo seleccionado</p>".
				"<input type='hidden' name='idGrupo' id='hiddenGrupoBorrar' />".
				"<input type='hidden' name='action' value='Borrar Grupo' />".
				"<input type='hidden' name='indiceAsignatura' />".
				"</form></div><div class='modal-footer'>".
				"<button class='btn btn-primary' onclick='borrarGrupo()' >".
				"Borrar</button>".
				"<button class='btn' data-dismiss='modal' aria-hidden='true'>".
				"Cancelar</button></div></div>";
		
		return $html;
	}

	public function crearModalParaBorrarNota()
	{
		$html = "<div id='divModalBorrarNota' class='modal hide fade' tabindex='-1'".
				" role='dialog'  aria-hidden='true'>".
				"<div class='modal-header'>".
				"<button type='button' class='close' data-dismiss='modal'".
				" aria-hidden='true'>x</button><h3 id='myModalLabel'>Esta seguro</h3>".
				"</div><div class='modal-body'>".
				"<p>Borrar&iacute;a la nota seleccionada</p>".
				"</div><div class='modal-footer'>".
				"<button class='btn btn-primary' onclick='borrarNota()' >".
				"Borrar</button>".
				"<button class='btn' data-dismiss='modal' aria-hidden='true'>".
				"Cancelar</button></div></div>";
		
		return $html;
	}

	public function crearModalParaEditarNota()
	{
		$html = "<div id='divModalEditarNota' class='modal hide fade' tabindex='-1'".
				" role='dialog'  aria-hidden='true'>".
				"<div class='modal-header'>".
				"<button type='button' class='close' data-dismiss='modal'".
				" aria-hidden='true'>x</button><h3 id='myModalLabel'>Editar Nota</h3></div>".
				"<div class='modal-body'>".
				"<form name='formEditarNota' id='formEditarNota' enctype='multipart/form-data'".
				" method='post' action='".HelperModules::$ROOT_SITE."/modules/asignatura/ControladorAsignatura.php'>".
				"<label>Nombre</label>".
				"<div><input name='nombre' type='text' maxlength='25' required />".
				"</div><label>Valor </label><div>".
				"<input name='valor' type='number' required maxlength='4' />".
				"</div><label id='labelPorcentaje'>Porcentaje </label><div>".
				"<input name='porcentaje' type='number' maxlength='3' required />".
				"</div><label>Fecha </label><div>".
				"<input type='text' class='inputCalendars' name='fecha' required /></div>".
				"<label>Grupo </label><div>".
				"<input name='grupoEditar' type='number' required maxlength='3' /></div>".
				"<input type='hidden' name='idNota' />".
				"<input type='hidden' value='Editar Nota' name='action' /></form></div>".
				"<div class='modal-footer'>".
				"<button class='btn btn-primary' onclick='editarNota();' >".
				"Editar Nota</button>".
				"<button class='btn' data-dismiss='modal' aria-hidden='true'>".
				"Cancelar</button></div></div>";
		
		return $html;
	}

}
