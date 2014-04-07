<?php

namespace domain\classes;

class GrupoDeNotas
{

	private $_id;
	private $_nombre;
	
	/**
	 * Variable booleana, es true si el porcentaje de las notas contenidas en el grupo son iguales
	 * y es falso si sucede lo contrario
	 * @var Boolean
	 */
	private $_porcentajesIguales;
	private $_asignatura;
	
	/**
	 * Una asignatura puede tener muchos grupos, pero debe tener por lo menos 1, y es un grupo por defecto.
	 * Esta variable dice si un grupo es el grupo por defecto de alguna asignatura
	 * @var Boolean
	 */
	private $_esGrupoDefecto;

	public function __construct($id, $nombre, $porcentajesIguales, $esGrupoDefecto)
	{
		$this->_id = $id;
		$this->_nombre= $nombre;
		$this->_porcentajesIguales = $porcentajesIguales;
		$this->_esGrupoDefecto = $esGrupoDefecto;
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
	
	public function getPorcentajesIguales()
	{
		return $this->_porcentajesIguales;
	}
	
	public function getAsignatura()
	{
		return $this->_asignatura;
	}
	
	public function setAsignatura(Asignatura $asignatura)
	{
		$this->_asignatura = $asignatura;
	}
	
	public function getEsGrupoDefecto()
	{
		return $this->_esGrupoDefecto;
	}
	
}