<?php

namespace domain\dto;

class DTOModuloHome
{

	private $_listaDePeriodosDeUnUsuario;
	private $_listaNotasFuturo;

	public function getListaDePeriodosDeUnUsuario()
	{
		return $this -> _listaDePeriodosDeUnUsuario;
	}

	public function setListaDePeriodosDeUnUsuario($listaDePeriodosDeUnUsuario)
	{
		$this -> _listaDePeriodosDeUnUsuario = $listaDePeriodosDeUnUsuario;
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