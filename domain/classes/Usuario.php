<?php

namespace domain\classes;

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

	public function __construct($email, $nick, $nombre, $password) 
	{
		$this -> _email = $email;
		$this -> _nick = $nick;
		$this -> _password = $password;
		$this -> _nombre = $nombre;
	}

	public function getEmail() 
	{
		return $this -> _email;
	}

	public function getNick() 
	{
		return $this -> _nick;
	}

	public function getPassword() 
	{
		return $this -> _password;
	}

	public function getNombre() 
	{
		return $this -> _nombre;
	}

	public function setPassword($password) 
	{
		$this -> _password = $password;
	}

	public function __toString()
	{
		return $this->getEmail();
	}

	public static function userToArray(Usuario $usuario)
	{
		$array = array(
				"email" => $usuario->getEmail(),
				"nick" => $usuario->getNick(),
				"password" => $usuario->getPassword(),
				"nombre" => $usuario->getNombre(),
		);

		return $array;
	}

	public static function arrayToUser($array)
	{
		$usuario = new Usuario($array['email'], $array['nick'], $array['nombre'], $array['password']);
		return $usuario;
	}

}
