<?php

namespace modules\periodo;

use domain\classes\Asignatura;

use domain\classes\Periodo;
use domain\business_objects\BoLogicaNotas;
use domain\classes\Usuario;
use domain\business_objects\BoUsuarios;

require_once '/../../domain/business_objects/BoUsuarios.php';
require_once '/../../domain/business_objects/BoLogicaNotas.php';

class ModeloPeriodo
{
	private $_boUsuarios;
	private $_boLogicaNotas;

	public function __construct()
	{
		$this->_boUsuarios = new BoUsuarios();
		$this->_boLogicaNotas = new BoLogicaNotas();
	}

	public function comprobarSiUnPeriodoPerteneceAUnUsuario($idPeriodo, $email)
	{
		return $this->_boLogicaNotas->comprobarSiUnPeriodoPerteneceAUnUsuario($idPeriodo,$email);
	}

	public function obtenerPeriodoPorId($id)
	{
		return $this->_boLogicaNotas->obtenerPeriodoPorId($id);
	}

	public function calcularPromedioFinalDeUnPeriodo($periodo){
		return $this->_boLogicaNotas->calcularPromedioFinalDeUnPeriodo($periodo);
	}

	public function editarPeriodo(Periodo $periodo)
	{
		return $this->_boLogicaNotas->editarPeriodo($periodo);
	}

	public function crearAsignatura(Asignatura $asignatura)
	{
		return $this->_boLogicaNotas->crearAsignatura($asignatura);
	}

	public function obtenerListaDeAsignaturasDeUnPeriodo(Periodo $periodo)
	{
		return $this->_boLogicaNotas->obtenerListaDeAsignaturasDeUnPeriodo($periodo);
	}

	public function borrarPeriodo(Periodo $periodo)
	{
		return $this->_boLogicaNotas->borrarPeriodo($periodo);
	}

}
