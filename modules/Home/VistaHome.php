<?php

namespace modules\Home;

use Dominio\DTO\DTOModuloHome;

use modules\HelperModules;

use Dominio\Clases\Periodo;

class VistaHome
{

	public function imprimirHTML_UsuarioLogueado(DTOModuloHome $dtoHome){
		$html= HelperModules::leerPlantillaHTML("Home","Home_User_Logueado");

		$listaDePeriodos = $this->crearListaDePeriodos($dtoHome->getListaDePeriodosDeUnUsuario());
		//$listaDeNoticias = $this->crearFeedDeNoticias($datos[2]);
		$listaDeNoticias = "";
		$listaNotasFuturas= $this->crearNotasFuturas($dtoHome->getListaNotasFuturo());

		$html = str_replace("<!--{Lista De Periodos}-->", $listaDePeriodos, $html);
		$html = str_replace("<!--{Notas Proximas}-->", $listaNotasFuturas, $html);
		//$html = str_replace("<!--{Feed de noticias}-->", $listaDeNoticias, $html);

		echo $html;
	}

	public function imprimirHTML_UsuarioNoLogueado(){
		echo HelperModules::leerPlantillaHTML("Home","Home_User_No_Logueado");
	}

	private function crearListaDePeriodos($listaDePeriodos){
		$html = "<ul>";
		foreach ($listaDePeriodos as $periodoLeido){
			$itemPeriodo = "<li><a href = '/periodo/".$periodoLeido->getId()."'>".$periodoLeido->getNombre().
			" [" . $periodoLeido->getFechaInicio()." / ".$periodoLeido->getFechaFinal()." ]</a></li>";
			$html.=$itemPeriodo;
		}
		$html.= "</ul>";
		return $html;
	}

	private function crearFeedDeNoticias($listaDeNoticias){
		$html = "<ul>";
		$numeroNoticias = count($listaDeNoticias);
		for ($i = 0; $i<$numeroNoticias; $i++){

		}
		$html+="</ul>";
		return $html;
	}


	private function crearNotasFuturas($listaDeNotas){
		$html = "";
		$numeroNotas= count($listaDeNotas);
		if($numeroNotas>0){
			$html.="<ul>";
			foreach ($listaDeNotas as $nota){
				$itemLista = "<li>".$nota->getGrupo()->getAsignatura()->getNombre()." | ". $nota->getNombre()
				." -> ".$nota->getFecha()."</li>";
				$html.=$itemLista;
			}
			$html.="</ul>";
		}else{
			$html = "<p style='margin:5px'>No hay eventos futuros</p>";
		}
		return $html;
	}

}
