<?php

/**
 * Este DTO se utiliza en el metodo insert. Sirve para obtener el ultimo id de la ultima
 * fila insertada en una tabla, y tambien el exito de la consulta
 *
 */

namespace Dominio\DTO;

class DTOCrud 
{

	/**
	 * Valor booleano. Es verdadero si la transaccion se hizo con exito, y falso si hubo un error
	 */
	private $_exitoConsulta;

	/**
	 * Almacena un id, que es el ID de la ultima fila ingresada en alguna tabla. Se utiliza en el metodo insert
	 */
	private $_ultimoId;

	public function __construct( $exitoConsulta)
	{
		$this -> _exitoConsulta = $exitoConsulta;
	}

	public function getUltimoId() 
	{
		return $this -> _ultimoId;
	}

	public function getExitoConsulta() {
		
		return $this -> _exitoConsulta;
	}

	public function setUltimoId($id) 
	{
		$this -> _ultimoId = $id;
	}

}
