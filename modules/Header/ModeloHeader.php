<?php

namespace modules\Header;

use Dominio\ObjetosDeNegocio\BoSocial;
use Dominio\Clases\Usuario;
use Dominio\ObjetosDeNegocio\BoUsuarios;

$_SERVER['DOCUMENT_ROOT'] = 'C:/xampp/htdocs/Qualify';

require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/ObjetosDeNegocio/BoUsuarios.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/ObjetosDeNegocio/BoSocial.php';

class ModeloHeader
{
	private $_boUsuarios;
	private $_boSocial;


	public function __construct()
	{
		$this->_boUsuarios = new BoUsuarios();
		$this->_boSocial = new BoSocial();
	}

	public function obtenerNotificacionesDelUsuario(Usuario $usuario)
	{
		//return $this->_boSocial->obtenerListaDeNotificacionesDeUnUsuario($usuario);
	}
}