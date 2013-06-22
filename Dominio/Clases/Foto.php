<?php

namespace Dominio\Clases;

class Foto
{

	private $_id;
	private $_ubicacion;

	public function __construct($id, $ubicacion)
	{
		$this ->_id = $id;
		$this -> _ubicacion = $ubicacion;
	}

	public function getId()
	{
		return $this -> _id;
	}

	public function setId($id)
	{
		$this -> _id = $id;
	}

	public function getUbicacion()
	{
		return $this -> _ubicacion;
	}

	public function setUbicacion($ubicacion)
	{
		$this -> _ubicacion = $ubicacion;
	}

	public function __toString()
	{
		return $this -> _ubicacion;
	}

}
