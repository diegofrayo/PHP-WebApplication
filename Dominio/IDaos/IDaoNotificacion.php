<?php

namespace Dominio\IDaos;

use Dominio\Clases\Usuario;
use Dominio\Clases\Notificacion;

interface IDaoNotificacion
{
	public function crear(Notificacion $notificacion);
	public function obtenerListaDeNotificacionesDeUnUsuario(Usuario $usuario);
	public function editar(Notificacion $notificacion);
}