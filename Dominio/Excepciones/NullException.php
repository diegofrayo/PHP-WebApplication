<?php

/**
 * Esta excepcion se lanza cuando se ingresan parametros nulos en un metodo
 */

namespace Dominio\Excepciones;

class NullException extends \Exception
{
	private $_mensaje;
	
	public function __construct($objetoEsperado)
	{
		$this->_mensaje = "<b>".$this->obtenerClase($this)."</b><br />El objeto ingresado no es valido (null).<br />Se esperaba un objeto de tipo: "
				.$this->obtenerClase($objetoEsperado) ;
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