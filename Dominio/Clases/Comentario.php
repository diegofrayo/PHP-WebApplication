<?php

namespace Dominio\Clases;
//require_once 'Noticia.php';
//require_once 'Usuario.php';

class Comentario
{

	private $_id;
	private $_texto;

	/**
	 * Fecha con hora
	 */
	private $_fecha;

	/**
	 * Noticia a la cual le hicieron el comentario
	 * @var Noticia
	 */
	private $_noticia;

	/**
	 * Usuario que hace el comentario
	 * @var Usuario
	 */
	private $_usuarioComentarista;

	public function __construct($id, $texto, $fecha )
	{
		$this->_id = $id;
		$this->_texto= $texto;
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
	public function getTexto()
	{
		return $this->_texto;
	}
	public function getFecha()
	{
		return $this->_fecha;
	}
	public function getNoticia()
	{
		return $this->_noticia;
	}
	public function setNoticia(Noticia $noticia)
	{
		$this->_noticia = $noticia;
	}
	public function getUsuarioComentarista()
	{
		return $this->_usuarioComentarista;
	}
	public function setUsuarioComentarista(Usuario $usuario)
	{
		$this->_usuarioComentarista = $usuario;
	}
}