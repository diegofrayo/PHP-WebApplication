<?php

namespace Dominio\Clases;

class Usuario
{

	/**
	 * Llave principal
	 */
	private $_email;
	
	/**
	 * LLave unica, no se puede repetir
	 */
	private $_nick;
	
	private $_nombre;
	
	private $_password;
	
	/**
	 * Arreglo de bytes, con la foto del usuario
	 */
	private $_foto;

	public function __construct($email, $nick, $nombre, $password, $foto) {
		$this -> _email = $email;
		$this -> _nick = $nick;
		$this -> _password = $password;
		$this -> _nombre = $nombre;
		$this -> _foto = $foto;
	}

	public function getEmail() {
		return $this -> _email;
	}

	public function getNick() {
		return $this -> _nick;
	}

	public function getPassword() {
		return $this -> _password;
	}
	
	public function getNombre() {
		return $this -> _nombre;
	}
	
	public function getFoto() {
		return $this -> _foto;
	}
	
	public function setFoto($foto) {
		 $this -> _foto = $foto;
	}
	

}
