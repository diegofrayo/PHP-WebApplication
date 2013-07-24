<?php

namespace modules\Home;

use Dominio\Clases\Foto;
use Dominio\Clases\Periodo;
use Dominio\ObjetosDeNegocio\BoLogicaNotas;
use Dominio\Clases\Usuario;
use Dominio\ObjetosDeNegocio\BoUsuarios;

$_SERVER['DOCUMENT_ROOT'] = 'C:/xampp/htdocs/Qualify';

require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/ObjetosDeNegocio/BoUsuarios.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/ObjetosDeNegocio/BoLogicaNotas.php';

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

	public function comprarDisponibilidadNick($nick)
	{
		return $this->_boUsuarios->comprarDisponibilidadNick($nick);
	}
	
	public function comprarDisponibilidadEmail($email)
	{
		return $this->_boUsuarios->obtenerUsuarioPorEmail($email);
	}

	public function obtenerNotasFuturas($nick)
	{
		$fecha = Date('Y-m-d');
		return $this->_boLogicaNotas->obtenerNotasFuturas($fecha,$nick);
	}

	public function crearFoto(Foto $foto, $configuracion)
	{
		return $this->_boUsuarios->crearFoto($foto, $configuracion);
	}
	
	public function borrarFoto(Foto $foto)
	{
		return $this->_boUsuarios->borrarFoto($foto);
	}
}
