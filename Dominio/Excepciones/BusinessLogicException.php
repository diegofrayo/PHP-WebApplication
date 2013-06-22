<?php

/**
 * Esta excepcion se lanza en los objetos de negocio, cuando por ejemplo se 
 * intenta crear una entidad que ya existe, o editar una entidad que no existe
 */

namespace Dominio\Excepciones;

class BusinessLogicException extends \Exception
{

	private $_mensaje;

	public function __construct($mensaje)
	{
		$this->_mensaje = "<div class='divTextoMensajesError'><strong>".$this->obtenerClase($this).
		"</strong><br />".$mensaje."</div>";
	}

	public function __toString()
	{
		return $this->_mensaje;
	}

	/**
	 * Este metodo retorna un string con el nombre de una clase
	 * ejemplo:
	 * si se ingresa: dd/dd/clase
	 * retorna clase
	 * @param Objeto
	 * @return String
	 */
	private function obtenerClase ($object){
		$nombreClase = get_class($object);
		$cadenas = explode("\\", $nombreClase);
		return $cadenas[count($cadenas)-1];
	}
}