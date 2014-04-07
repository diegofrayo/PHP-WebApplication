<?php

namespace modules\header;

use domain\dto\DTOModuloHeader;
use modules\HelperModules;

class VistaHeader
{

	public function imprimirHTML_UsuarioLogueado(DTOModuloHeader $dtoHeader)
	{
		$html = HelperModules::leerPlantillaHTML("header","Header_User_Logueado");

		$linkUsuario = $this->crearLinkUsuario($dtoHeader->getNickUsuario());

		$html = str_replace("<!--{Link Usuario}-->", $linkUsuario, $html);
		$html = str_replace("<!--{Root Site}-->", HelperModules::$ROOT_SITE, $html);

		echo $html;
	}

	public function imprimirHTML_UsuarioNoLogueado()
	{
		$html = HelperModules::leerPlantillaHTML("header","Header_User_No_Logueado");
		$html = str_replace("<!--{Root Site}-->", HelperModules::$ROOT_SITE, $html);
		
		echo $html;
	}

	private function crearLinkUsuario($nick)
	{
		$html = "<div id='divHijoNavProfile'><a href = '#' >".
				"<img alt='image-profile' src='".HelperModules::$ROOT_SITE."/public/avatar.png' /><p>@".$nick."</p></a></div>";
		
		return $html;
	}

}
