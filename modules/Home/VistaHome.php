<?php

namespace modules\home;

use domain\dto\DTOModuloHome;
use modules\HelperModules;
use domain\classes\Periodo;

class VistaHome
{

	public function imprimirHTML_UsuarioLogueado(DTOModuloHome $dtoHome)
	{
		$html= HelperModules::leerPlantillaHTML("home","Home_User_Logueado");

		$listaDePeriodos = $this->crearListaDePeriodos($dtoHome->getListaDePeriodosDeUnUsuario());
		$listaNotasFuturas= $this->crearNotasFuturas($dtoHome->getListaNotasFuturo());

		$html = str_replace("<!--{Lista De Periodos}-->", $listaDePeriodos, $html);
		$html = str_replace("<!--{Notas Proximas}-->", $listaNotasFuturas, $html);
		$html = str_replace("<!--{Root Site}-->", HelperModules::$ROOT_SITE, $html);

		echo $html;
	}

	public function imprimirHTML_UsuarioNoLogueado()
	{
		$html= HelperModules::leerPlantillaHTML("home","Home_User_No_Logueado");
		$html = str_replace("<!--{Root Site}-->", HelperModules::$ROOT_SITE, $html);
		
		echo $html;
	}

	private function crearListaDePeriodos($listaDePeriodos)
	{
		$html = "<ul>";
		
		foreach ($listaDePeriodos as $periodoLeido){
			
			$itemPeriodo = "<li><a href = 'periodo/".$periodoLeido->getId()."'>".$periodoLeido->getNombre().
			" [" . $periodoLeido->getFechaInicio()." / ".$periodoLeido->getFechaFinal()." ]</a></li>";
			$html.=$itemPeriodo;
		}
		
		$html.= "</ul>";
		return $html;
	}

	private function crearNotasFuturas($listaDeNotas)
	{
		$html = "";
		$numeroNotas= count($listaDeNotas);
		
		if($numeroNotas>0){
			
			$html.="<ul>";
			$fechaActual = date ('Y-m-d');
			$textoFechas = "";
			
			foreach ($listaDeNotas as $nota){
				
				$fechaNota = $nota->getFecha();
				$diasDeDiferencias = $this->diferenciaDiasEntreFechas($fechaActual, $fechaNota);
				
				switch ($diasDeDiferencias){
					
					case 0:
						$textoFechas = "<u>&#33; Es hoy &#161;</u>";
						break;
						
					case 1:
						$textoFechas = "<strong>Es ma&ntilde;ana</strong>";
						break;

					default:
						$textoFechas = "<strong>Faltan ".$diasDeDiferencias." d&#237;as</strong>";
						break;
				}

				$itemLista = "<li>".$nota->getGrupo()->getAsignatura()->getNombre()." | ". $nota->getNombre()
				." | ".$fechaNota." | ".$textoFechas."</li>";
				$html.=$itemLista;
			}
			
			$html.="</ul>";
		}else{
			
			$html = "<p style='margin:5px'>No hay eventos futuros</p>";
		}
		
		return $html;
	}

	function diferenciaDiasEntreFechas($start, $end) 
	{
		
		$start_ts = strtotime($start);
		$end_ts = strtotime($end);
		$diff = $end_ts - $start_ts;
		return round($diff / 86400);
	}

}