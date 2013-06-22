<?php

namespace Dominio\Clases;

class Periodo
{

	private $_id;
	
	/**
	 * Fecha sin hora, en cual inicia el periodo
	 */
	private $_fechaInicio;
	
	/**
	 * Fecha sin hora, en cual acaba el periodo
	 */
	private $_fechaFinal;
	

	/**
	 * Atributo de tipo Usuario. Es el usuario propietario del periodo
	 */
	private $_usuario;
	
	private $_nombre;

	public function __construct($id, $fechaInicio, $fechaFinal, $nombre)
	{
		$this->_id = $id;
		$this->_fechaInicio= $fechaInicio;
		$this->_fechaFinal = $fechaFinal;
		$this->_nombre = $nombre;
	}

	public function getId()
	{
		return $this->_id;
	}
	public function setId($id)
	{
		$this -> _id = $id;
	}
	public function getFechaInicio()
	{
		return $this->_fechaInicio;
	}
	public function getFechaFinal()
	{
		return $this->_fechaFinal;
	}

	public function getUsuario()
	{
		return $this->_usuario;
	}
	public function getNombre()
	{
		return $this->_nombre;
	}
	public function setUsuario(Usuario $usuario)
	{
		$this->_usuario = $usuario;
	}
}