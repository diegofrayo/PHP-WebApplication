<?php

namespace Dominio\Clases;
//require_once 'GrupoDeNotas.php';

class Nota 
{

	private $_id;
	private $_nombre;

	/**
	 * Valor o puntaje
	 */
	private $_valor;

	/**
	 * Porcentaje de la nota en el grupo que pertenece
	 */
	private $_porcentaje;

	/**
	 * Variable de tipo GrupoDeNotas
	 */
	private $_grupo;

	/**
	 * Solo guarda la fecha
	 */
	private $_fecha;

	public function __construct($id, $nombre, $valor, $porcentaje, $fecha)
	{
		$this->_id = $id;
		$this->_nombre= $nombre;
		$this->_valor = $valor;
		$this->_porcentaje = $porcentaje;
		$this->_fecha = $fecha;
	}

	public function getId()
	{
		return $this->_id;
	}
	public function setId($id) 
	{
		$this -> _id = $id;
	}
	public function getNombre()
	{
		return $this->_nombre;
	}
	public function getValor()
	{
		return $this->_valor;
	}
	public function getPorcentaje()
	{
		return $this->_porcentaje;
	}
	public function getFecha()
	{
		return $this->_fecha;
	}
	public function getGrupo()
	{
		return $this->_grupo;
	}
	public function setGrupo(GrupoDeNotas $grupo)
	{
		$this->_grupo = $grupo;
	}

	public function __toString()
	{
		return $this->_nombre;
	}
}