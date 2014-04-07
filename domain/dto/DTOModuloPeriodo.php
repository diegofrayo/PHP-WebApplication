<?php

namespace domain\dto;

class DTOModuloPeriodo
{

	private $_periodo;
	private $_listaAsignaturasDeUnPeriodo;

	public function getPeriodo()
	{
		return $this -> _periodo;
	}

	public function getListaAsignaturasDeUnPeriodo()
	{
		return $this -> _listaAsignaturasDeUnPeriodo;
	}

	public function setPeriodo($periodo)
	{
		$this -> _periodo = $periodo;
	}

	public function setListaAsignaturasDeUnPeriodo($listaAsignaturasDeUnPeriodo) 
	{
		$this -> _listaAsignaturasDeUnPeriodo = $listaAsignaturasDeUnPeriodo;
	}

}