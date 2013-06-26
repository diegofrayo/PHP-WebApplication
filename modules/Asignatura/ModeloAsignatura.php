<?php

namespace modules\Asignatura;

use Dominio\Clases\GrupoDeNotas;
use Dominio\Clases\Nota;
use Dominio\Clases\Asignatura;
use Dominio\ObjetosDeNegocio\BoLogicaNotas;
use Dominio\Clases\Usuario;

require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/ObjetosDeNegocio/BoLogicaNotas.php';

class ModeloAsignatura
{
	private $_boLogicaNotas;

	public function __construct()
	{
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

	public function borrarAsignatura(Asignatura $asignatura)
	{
		return $this->_boLogicaNotas->borrarAsignatura($asignatura);
	}

	public function borrarNota(Nota $nota)
	{
		return $this->_boLogicaNotas->borrarNota($nota);
	}

	public function borrarGrupoDeNotas(GrupoDeNotas $grupoDeNotas)
	{
		return $this->_boLogicaNotas->borrarGrupoDeNotas($grupoDeNotas);
	}

	public function editarGrupoDeNotas(GrupoDeNotas $grupoDeNotas)
	{
		return $this->_boLogicaNotas->editarGrupoDeNotas($grupoDeNotas);
	}

	public function crearGrupoDeNotas(GrupoDeNotas $grupoDeNotas)
	{
		return $this->_boLogicaNotas->crearGrupoDeNotas($grupoDeNotas);
	}

	public function editarNota(Nota $nota)
	{
		return $this->_boLogicaNotas->editarNota($nota);
	}
}
