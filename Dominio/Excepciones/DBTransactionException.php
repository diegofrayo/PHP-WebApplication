<?php

/**
 * Esta excepcion se lanza cuando se hace una consulta (CRUD), y la transaccion falla y retorna false
 */

namespace Dominio\Excepciones;

class DBTransactionException extends \Exception
{

	private $_mensaje;

	public function __construct()
	{
		$this->_mensaje = "<div class='divTextoMensajesError alert'>".
				"<button type='button' class='close' data-dismiss='alert'>&times;</button>".
				"<strong>".$this->obtenerClase($this).
				"</strong><br />La transaccion solicitada ha fallado, revise los parametros ingresados</div>";
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