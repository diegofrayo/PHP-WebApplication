<?php

namespace modules\Header;

use Dominio\DTO\DTOModuloHeader;

use modules\HelperModules;

class VistaHeader
{

	public function imprimirHTML_UsuarioLogueado(DTOModuloHeader $dtoHeader){
		$html = HelperModules::leerPlantillaHTML("Header","Header_User_Logueado");

		$linkUsuario = $this->crearLinkUsuario($dtoHeader->getNickUsuario());
		//$listaDeNoticias = $this->crearFeedDeNoticias($dtoHeader->getListaNotificacionesUsuario());

		$html = str_replace("<!--{Link Usuario}-->", $linkUsuario, $html);

		echo $html;
	}

	public function imprimirHTML_UsuarioNoLogueado(){
		echo HelperModules::leerPlantillaHTML("Header","Header_User_No_Logueado");
	}

	private function crearLinkUsuario($nick){
		$html = "<a href = '/perfil/".$nick."'>@".$nick."</a>";
		return $html;
	}

}
