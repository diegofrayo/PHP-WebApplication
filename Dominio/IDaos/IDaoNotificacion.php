<?php

namespace Dominio\IDaos;

interface IDaoNotificacion
{
	public function crear(Notificacion $notificacion);
	public function obtenerListaDeNotificacionesDeUnUsuario(Usuario $usuario);
	public function editar(Notificacion $notificacion);
}