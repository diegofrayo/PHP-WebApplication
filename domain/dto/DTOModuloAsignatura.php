<?php

namespace domain\dto;

class DTOModuloAsignatura
{

	private $_listaDeGrupos;
	private $_listaDePeriodosDeUnUsuario;
	private $_asignatura;

	/**
	 * Este atributo es una matriz con notas.
	 * En cada fila tiene un array con las notas de un grupo
	 * @var unknown_type
	 */
	private $_matrizListaDeNotasDeUnGrupo;

	/**
	 * Indice de la asignatura, del array de asignaturas de un periodo
	 * @var unknown_type
	 */
	private $_indiceAsignatura;

	public function getListaDeGrupos()
	{
		return $this -> _listaDeGrupos;
	}

	public function getListaDePeriodosDeUnUsuario() 
	{
		return $this -> _listaDePeriodosDeUnUsuario;
	}

	public function setListaDeGrupos($listaDeGrupos)
	{
		$this -> _listaDeGrupos = $listaDeGrupos;
	}

	public function setListaDePeriodosDeUnUsuario($listaDePeriodosDeUnUsuario)
	{
		$this -> _listaDePeriodosDeUnUsuario = $listaDePeriodosDeUnUsuario;
	}

	public function getMatrizListaDeNotasDeUnGrupo() {

		return $this -> _matrizListaDeNotasDeUnGrupo;
	}

	public function setMatrizListaDeNotasDeUnGrupo($matrizListaDeNotasDeUnGrupo)
	{
		$this -> _matrizListaDeNotasDeUnGrupo = $matrizListaDeNotasDeUnGrupo;
	}

	public function getAsignatura() {

		return $this -> _asignatura;
	}

	public function setAsignatura($asignatura)
	{
		$this -> _asignatura = $asignatura;
	}
	
	public function getIndice() 
	{	
		return $this -> _indiceAsignatura;
	}
	
	public function setIndice($indice)
	{
		$this -> _indiceAsignatura = $indice;
	}

}