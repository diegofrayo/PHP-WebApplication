<?php

namespace modules\home;

use domain\classes\Periodo;
use domain\business_objects\BoLogicaNotas;
use domain\classes\Usuario;
use domain\business_objects\BoUsuarios;

require_once '/../../domain/business_objects/BoUsuarios.php';
require_once '/../../domain/business_objects/BoLogicaNotas.php';

class ModeloHome
{
	private $_boUsuarios;
	private $_boLogicaNotas;

	public function __construct()
	{
		$this->_boUsuarios = new BoUsuarios();
		$this->_boLogicaNotas = new BoLogicaNotas();
	}

	public function registrarUsuario(Usuario $usuario)
	{
		return $this->_boUsuarios->registrarUsuario($usuario);
	}

	public function iniciarSesionUsuario($nick, $password)
	{
		return $this->_boUsuarios->iniciarSesionUsuario($nick, $password);
	}

	public function obtenerListaDePeriodosDeUnUsuario(Usuario $usuario)
	{
		return $this->_boLogicaNotas->obtenerListaDePeriodosDeUnUsuario($usuario);
	}

	public function crearPeriodo(Periodo $periodo)
	{
		return $this->_boLogicaNotas->crearPeriodo($periodo);
	}

	public function comprarDisponibilidadNick($nick)
	{
		return $this->_boUsuarios->comprarDisponibilidadNick($nick);
	}
	
	public function comprarDisponibilidadEmail($email)
	{
		return $this->_boUsuarios->obtenerUsuarioPorEmail($email);
	}

	public function obtenerNotasFuturas($email)
	{
		return $this->_boLogicaNotas->obtenerNotasFuturas($email);
	}

}
