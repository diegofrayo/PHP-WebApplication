<?php

/**
 * Esta excepcion se lanza cuando se ingresan parametros, donde
 * el tipo de datos no es valido o aceptado por el metodo
 *
 * Ej: Se ingresa por parametro un String, y el metodo espera un numero, o viceversa
 */

namespace domain\exceptions;

class ObjectNotFoundException extends \Exception
{

	private $_mensaje;

	public function __construct()
	{
		$this->_mensaje = "<div class='divTextoMensajes'><strong>".$this->obtenerClase($this).
		"</strong><br />El tipo de dato ingresado no es valido</div>";
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