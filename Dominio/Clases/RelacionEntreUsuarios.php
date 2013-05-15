<?php

namespace Dominio\Clases;
//require_once 'Usuario.php';

class RelacionEntreUsuarios
{

	private $_id;
	
	/**
	 * Usuario que recibe la solicitud de amistad
	 * @var Usuario
	 */
	private $_usuarioReceptor;
	
	/**
	 * Usuario que envia la solicitud de amistad
	 * @var Usuario
	 */
	private $_usuarioEmisor;
	
	/**
	 * Variable de tipo booleana
	 * true: Si los 2 usuarios son amigos
	 * false: Si solo existe una solicitud, pero no hay amistad
	 * @var unknown_type
	 */
	private $_estado;

	public function __construct($id, $estado)
	{
		$this->_id = $id;
		$this->_estado = $estado;
	}

	public function getId()
	{
		return $this->_id;
	}
	public function setId($id)
	{
		$this -> _id = $id;
	}
	public function getUsuarioReceptor()
	{
		return $this->_usuarioReceptor;
	}
	public function setUsuarioReceptor(Usuario $usuario)
	{
		$this->_usuarioReceptor = $usuario;
	}
	public function getUsuarioEmisor()
	{
		return $this->_usuarioEmisor;
	}
	public function setUsuarioEmisor(Usuario $usuario)
	{
		$this->_usuarioEmisor = $usuario;
	}
	public function getEstado()
	{
		return $this->_estado;
	}
	public function __toString()
	{
		//return $this->_usuarioReceptor;
	}
}