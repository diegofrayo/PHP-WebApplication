<?php

namespace modules\Home;

use modules\HelperModules;

use Dominio\Clases\Periodo;

class VistaHome
{

	/**
	 * Datos es un array, con la informacion del usuario que se va a colocar en la plantilla
	 * @param unknown_type $datos
	 */
	public function imprimirHTML_UsuarioLogueado($datos){
		$html= HelperModules::leerPlantillaHTML("Home","Home_User_Logueado");

		$listaDePeriodos = $this->crearListaDePeriodos($datos[1]);
		//$listaDeNoticias = $this->crearFeedDeNoticias($datos[2]);
		$listaDeNoticias = "";

		$html = str_replace("<!--{Lista De Periodos}-->", $listaDePeriodos, $html);
		//$html = str_replace("<!--{Feed de noticias}-->", $listaDeNoticias, $html);

		echo $html;
	}

	public function imprimirHTML_UsuarioNoLogueado(){
		echo $this->leerPlantillaHTML("Home_User_No_Logueado");
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

	public function leerPlantillaHTML($nombreFile){
		return file_get_contents("html/".$nombreFile.".phtml", true);
	}

}
