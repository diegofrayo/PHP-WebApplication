<?php

namespace modules\Home;

use Dominio\Clases\Periodo;
use Dominio\ObjetosDeNegocio\BoLogicaNotas;
use Dominio\Clases\Usuario;
use Dominio\ObjetosDeNegocio\BoUsuarios;

require_once '/../../Dominio/ObjetosDeNegocio/BoUsuarios.php';
require_once '/../../Dominio/ObjetosDeNegocio/BoLogicaNotas.php';

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

	public function iniciarSesionUsuario($email, $password)
	{
			return $this->_boUsuarios->iniciarSesionUsuario($email, $password);
	}

	public function obtenerListaDePeriodosDeUnUsuario(Usuario $usuario)
	{
		return $this->_boLogicaNotas->obtenerListaDePeriodosDeUnUsuario($usuario);
	}
	
	public function crearPeriodo(Periodo $periodo)
	{
		return $this->_boLogicaNotas->crearPeriodo($periodo);
	}
}
