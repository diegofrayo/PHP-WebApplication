<?php

namespace modules;

/**
 * Clase creada con unos metodos que todos los controladores van a utilizar.
 * @author Diego Rayo
 */

class HelperModules
{

	public static $ROOT_SITE = "/Qualify";

	public static function crearMensajeExito($mensajeExito){
		$html = "<div class='divTextoMensajesExito alert'>".
				"<button type='button' class='close' data-dismiss='alert'>&times;</button>".
				"<strong>&Eacute;xito</strong><br />".$mensajeExito."</div>";
		return $html;
	}

	public static function crearMensajeError($mensajeError){
		$html = "<div class='divTextoMensajesError alert'>".
				"<button type='button' class='close' data-dismiss='alert'>&times;</button>".
				"<strong>Error</strong><br />".$mensajeError."</div>";
		return $html;
	}

	public static function  redireccionarAlInicio(){
		header("location: ".HelperModules::$ROOT_SITE."/home");
	}

	public static function  redireccionar($destino){
		header("location: ".HelperModules::$ROOT_SITE."/".$destino);
	}

	public static function leerPlantillaHTML($nombreModulo , $nombreFile){
		$html = file_get_contents($nombreModulo."/html/".$nombreFile.".phtml", true);
		$html = str_replace("\n", "\n ", $html);
		//$html = str_replace("\t", "", $html);
		return $html;
	}

	public static function crearScriptAsignatura($indiceAsignatura){
		$indice = $indiceAsignatura + 1;
		$html = "<script>desplazarCarousel(".$indice.");</script>";
		return $html;
	}

}
