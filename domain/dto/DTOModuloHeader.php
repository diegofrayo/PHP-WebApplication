<?php

namespace domain\dto;

class DTOModuloHeader
{

	private $_nickUsuario;

	public function getNickUsuario()
	{
		return $this -> _nickUsuario;
	}

	public function setNickUsuario($nickUsuario)
	{
		$this -> _nickUsuario = $nickUsuario;
	}

}