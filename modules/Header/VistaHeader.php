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

		// 		$html = "<a href = '/perfil/".$nick."'><div id='divProfile'>".
		// 				"<img alt='image-profile' src='".$_SERVER['DOCUMENT_ROOT']."/".$ubicacionFoto."'/>.
		// 						<p>@".$nick."</p></div></a>";

		$html = "<a href = '/perfil/".$nick."'><div id='divImgProfile'>".
				"<img alt='image-profile' src='http://qualify.hol.es/".$ubicacionFoto."'/>.
						<p>@".$nick."</p></div></a>";
		return $html;
	}

}
