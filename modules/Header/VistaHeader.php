<?php

namespace modules\Header;

use Dominio\DTO\DTOModuloHeader;
use modules\HelperModules;

class VistaHeader
{

	public function imprimirHTML_UsuarioLogueado(DTOModuloHeader $dtoHeader){
		$html = HelperModules::leerPlantillaHTML("Header","Header_User_Logueado");

		$linkUsuario = $this->crearLinkUsuario($dtoHeader->getNickUsuario(), $dtoHeader->getFotoUsuario());

		$html = str_replace("<!--{Link Usuario}-->", $linkUsuario, $html);
		$html = str_replace("<!--{Root Site}-->", HelperModules::$ROOT_SITE, $html);

		echo $html;
	}

	public function imprimirHTML_UsuarioNoLogueado(){
		$html =HelperModules::leerPlantillaHTML("Header","Header_User_No_Logueado");
		$html = str_replace("<!--{Root Site}-->", HelperModules::$ROOT_SITE, $html);
		echo $html;
	}

	private function crearLinkUsuario($nick, $ubicacionFoto){
		$html = "<div id='divHijoNavProfile'><a href = '#' >".
				"<img alt='image-profile' src='".HelperModules::$ROOT_SITE."/".$ubicacionFoto."' /><p>@".$nick."</p></a></div>";
		return $html;
	}

}
