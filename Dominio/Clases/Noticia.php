<?php

namespace Dominio\Clases;
//require 'Notaphp';
//require 'Usuario.php';

class Noticia
{

	private $_id;

	/**
	 * Usuario que publica la noticia
	 * @var Usuario
	 */
	private $_usuario;

	/**
	 * La nota publicada en la noticia
	 * @var Nota
	 */
	private $_nota;

	public function __construct($id)
	{
		$this->_id = $id;
	}

	public function getId()
	{
		return $this->_id;
	}
	public function setId($id)
	{
		$this -> _id = $id;
	}
	public function getUsuario()
	{
		return $this->_usuario;
	}
	public function setUsuario(Usuario $usuario)
	{
		$this->_usuario = $usuario;
	}
	public function getNota()
	{
		return $this->_nota;
	}
	public function setNota(Nota $nota)
	{
		$this->_nota = $nota;
	}
}