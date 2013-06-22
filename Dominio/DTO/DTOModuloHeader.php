<?php

namespace Dominio\DTO;

class DTOModuloHeader
{

	private $_nickUsuario;
	private $_listaNotificacionesUsuario;
	private $_fotoUsuario;

	public function __construct()
	{

	}

	public function getNickUsuario()
	{
		return $this -> _nickUsuario;
	}

	public function getListaNotificacionesUsuario() {

		return $this -> _listaNotificacionesUsuario;
	}

	public function setNickUsuario($nickUsuario)
	{
		$this -> _nickUsuario = $nickUsuario;
	}

	public function setListaNotificacionesUsuario($listaNotificacionesUsuario) {

		$this -> _listaNotificacionesUsuario = $listaNotificacionesUsuario;
	}

	public function getFotoUsuario()
	{
		return $this -> _fotoUsuario;
	}

	public function setFotoUsuario($foto)
	{
		$this -> _fotoUsuario = $foto;
	}


}