<?php

namespace Dominio\IDaos;

use Dominio\Clases\Usuario;

interface IDaoUsuario
{
	public function crear(Usuario $usuario);
	public function editar(Usuario $usuario);
	public function obtenerUsuarioPorEmail($email);
	public function buscarUsuariosPorNombre($nombre);
	public function recuperarPassword(Usuario $usuario);
	public function comprarDisponibilidadNick($nick);
}