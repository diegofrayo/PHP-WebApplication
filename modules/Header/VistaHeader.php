<?php

namespace modules\Header;

use modules\HelperModules;

class VistaHeader
{

	/**
	 * Datos es un array, con la informacion del usuario que se va a colocar en la plantilla
	 * @param unknown_type $datos
	 */
	public function imprimirHTML_UsuarioLogueado($datos){
		$html = HelperModules::leerPlantillaHTML("Header","Header_User_Logueado");

		$linkUsuario = $this->crearLinkUsuario($datos[1]);
		//$listaDeNoticias = $this->crearFeedDeNoticias($datos[2]);

		$html = str_replace("<!--{Link Usuario}-->", $linkUsuario, $html);

		echo $html;
	}

	public function imprimirHTML_UsuarioNoLogueado(){
		echo HelperModules::leerPlantillaHTML("Header","Header_User_No_Logueado");
	}

	public function leerPlantillaHTML($nombreFile){
		return file_get_contents("html/".$nombreFile.".phtml", true);
	}

	private function crearLinkUsuario($nick){
		$html = "<a href = '/perfil/".$nick."'>@".$nick."</a>";
		return $html;
	}

}
