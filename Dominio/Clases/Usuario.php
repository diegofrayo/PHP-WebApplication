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
	 * Objeto de tipo Foto.php
	 */
	private $_foto;

	public function __construct($email, $nick, $nombre, $password) {
		$this -> _email = $email;
		$this -> _nick = $nick;
		$this -> _password = $password;
		$this -> _nombre = $nombre;
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

	public function setPassword($password) {
		$this -> _password = $password;
	}

	public function __toString(){
		return $this->getEmail();
	}

	public static function userToArray(Usuario $usuario)
	{

		$foto = $usuario->getFoto();
		if($foto!=null){
			$foto = array("id" => $foto->getId(), "ubicacion" => $foto->getUbicacion());
		}
		$array = array(
				"email" => $usuario->getEmail(),
				"nick" => $usuario->getNick(),
				"password" => $usuario->getPassword(),
				"nombre" => $usuario->getNombre(),
				"foto" => $foto
		);

		return $array;
	}

	public static function arrayToUser($array)
	{
		$usuario = new Usuario($array['email'], $array['nick'], $array['nombre'], $array['password']);
		if($array['foto']!=null){
			$foto = new Foto($array['foto']['id'], $array['foto']['ubicacion']);
			$usuario->setFoto($foto);
		}
		return $usuario;
	}

}
