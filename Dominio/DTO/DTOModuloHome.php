<?php

namespace Dominio\DTO;

class DTOModuloHome
{

	private $_listaDePeriodosDeUnUsuario;
	private $_feedNoticias;
	private $_listaNotasFuturo;

	public function __construct()
	{

	}

	public function getListaDePeriodosDeUnUsuario()
	{
		return $this -> _listaDePeriodosDeUnUsuario;
	}

	public function getFeedNoticias() {

		return $this -> _feedNoticias;
	}

	public function setListaDePeriodosDeUnUsuario($listaDePeriodosDeUnUsuario)
	{
		$this -> _listaDePeriodosDeUnUsuario = $listaDePeriodosDeUnUsuario;
	}

	public function setFeedNoticias($feedNoticias) {

		$this -> _feedNoticias = $feedNoticias;
	}

	public function getListaNotasFuturo()
	{
		return $this -> _listaNotasFuturo;
	}

	public function setListaNotasFuturo($listaNotasFuturo)
	{
		$this -> _listaNotasFuturo = $listaNotasFuturo;
	}


}