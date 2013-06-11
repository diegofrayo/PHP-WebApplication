<?php

namespace modules\Asignatura;

use Dominio\Clases\GrupoDeNotas;

use Dominio\Clases\Nota;

use Dominio\Clases\Asignatura;
use Dominio\ObjetosDeNegocio\BoLogicaNotas;
use Dominio\Clases\Usuario;
use Dominio\ObjetosDeNegocio\BoUsuarios;

require_once '/../../Dominio/ObjetosDeNegocio/BoUsuarios.php';
require_once '/../../Dominio/ObjetosDeNegocio/BoLogicaNotas.php';

class ModeloAsignatura
{
	private $_boUsuarios;
	private $_boLogicaNotas;

	public function __construct()
	{
		$this->_boUsuarios = new BoUsuarios();
		$this->_boLogicaNotas = new BoLogicaNotas();
	}

	public function obtenerGrupoDeNotasPorId($id)
	{
		return $this->_boLogicaNotas->obtenerGrupoDeNotasPorId($id);
	}

	public function crearNota(Nota $nota)
	{
		return $this->_boLogicaNotas->crearNota($nota);
	}

	public function editarAsignatura(Asignatura $asignatura)
	{
		return $this->_boLogicaNotas->editarAsignatura($asignatura);
	}

	public function obtenerListaDeGruposDeUnaAsignatura(Asignatura $asignatura){
		return $this->_boLogicaNotas->obtenerListaDeGruposDeUnaAsignatura($asignatura);
	}
	
	public function obtenerListaDeNotasDeUnGrupo(GrupoDeNotas $grupo)
	{
		return $this->_boLogicaNotas->obtenerListaDeNotasDeUnGrupo($grupo);
	}
}
