<?php

namespace Dominio\Clases;
//require_once 'Periodo.php';

class Asignatura
{

	private $_id;
	private $_nombre;

	/**
	 * Variable de tipo Periodo
	 */
	private $_periodo;


	public function __construct($id, $nombre)
	{

		$this -> _id = $id;
		$this -> _nombre = $nombre;

	}

	public function getId()
	{
		return $this -> _id;
	}

	public function setId($id)
	{
		$this -> _id = $id;
	}

	public function getNombre()
	{
		return $this -> _nombre;
	}

	public function getPeriodo()
	{
		return $this -> _periodo;
	}

	public function setPeriodo(Periodo $periodo)
	{
		$this -> _periodo = $periodo;
	}

	public function __toString()
	{
		return $this -> _nombre;
	}

}
