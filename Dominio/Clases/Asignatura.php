<?php

namespace Dominio\Clases;
//require_once 'Periodo.php';

class Asignatura
{

	private $_id;
	private $_nombre;
	/**
	 * Numero de notas que conforman el porcentaje total de la asignatura
	 * @var Integer
	 */
	private $_numeroDeNotas;

	/**
	 * Variable de tipo Periodo
	 */
	private $_periodo;

	/**
	 * Nota definitiva de la asignatura
	 * @var Float
	 */
	private $_notaFinal;


	public function __construct($id, $nombre, $numeroDeNotas)
	{

		$this -> _id = $id;
		$this -> _nombre = $nombre;
		$this -> _numeroDeNotas = $numeroDeNotas;

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

	public function getNumeroDeNotas()
	{
		return $this -> _numeroDeNotas;
	}

	public function getPeriodo()
	{
		return $this -> _periodo;
	}

	public function setPeriodo(Periodo $periodo)
	{
		$this -> _periodo = $periodo;
	}

	public function getNotaFinal()
	{
		return $this -> _notaFinal;
	}

	public function setNotaFinal($notaFinal)
	{
		$this -> _notaFinal = $notaFinal;
	}

	public function __toString()
	{
		return $this -> _nombre;
	}

}
