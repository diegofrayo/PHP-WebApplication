<?php

namespace modules\Header;

use Dominio\DTO\DTOModuloHeader;

use modules\HelperModules;

class VistaHeader
{

	public function imprimirHTML_UsuarioLogueado(DTOModuloHeader $dtoHeader){
		$html = HelperModules::leerPlantillaHTML("Header","Header_User_Logueado");

		$linkUsuario = $this->crearLinkUsuario($dtoHeader->getNickUsuario(), $dtoHeader->getFotoUsuario());
		//$listaDeNoticias = $this->crearFeedDeNoticias($dtoHeader->getListaNotificacionesUsuario());

		$html = str_replace("<!--{Link Usuario}-->", $linkUsuario, $html);

		echo $html;
	}

	public function imprimirHTML_UsuarioNoLogueado(){
		echo HelperModules::leerPlantillaHTML("Header","Header_User_No_Logueado");
	}

	private function crearLinkUsuario($nick, $ubicacionFoto){
		$html = "<div id='divHijoNavProfile'><a href = '#' >".
				"<img alt='image-profile' src='http://qualify.hol.es/".$ubicacionFoto."' /><p>@".$nick."</p></a></div>";
		// 		$html = "<div id='divHijoNavProfile'><a href = '/perfil/".$nick."'>".
		// 				"<img alt='image-profile' src='http://qualify.hol.es/".$ubicacionFoto."' /><p>@".$nick."</p></a></div>";
		return $html;
	}

}
