<?php

namespace Dominio\Clases;
//require_once 'Usuario.php';

class Notificacion
{

	private $_id;
	/**
	 * Link que dirige a un perfil de un nuevo amigo, o una nota comentada
	 * @var String
	 */
	private $_link;
	
	/**
	 * Texto de la notificacion que se le va a mostrar al usuario
	 * @var String
	 */
	private $_texto;
	
	/**
	 * Atributo de tipo Usuario. Es el que recibe la notificacion
	 */
	private $_usuario;
	
	/**
	 * Variable booleana, si es true, es porque la notificacion no ha sido vista por el usuario
	 * @var Boolean
	 */
	private $_esNueva;
	
		/**
	 * Fecha con hora
	 */
	private $_fecha;

	public function __construct($id, $link, $texto, $esNueva, $fecha)
	{
		$this->_id = $id;
		$this->_link= $link;
		$this->_texto = $texto;
		$this->_esNueva = $esNueva;
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
	public function getLink()
	{
		return $this->_link;
	}
	public function getTexto()
	{
		return $this->_texto;
	}
	public function getUsuario()
	{
		return $this->_usuario;
	}
	public function setUsuario(Usuario $usuario)
	{
		$this->_usuario = $usuario;
	}
	public function getEsNueva()
	{
		return $this->_esNueva;
	}
	public function getFecha()
	{
		return $this->_fecha;
	}
}