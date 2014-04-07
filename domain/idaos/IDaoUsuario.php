<?php

namespace domain\idaos;

use domain\classes\Usuario;

interface IDaoUsuario {
	public function crear(Usuario $usuario);
	public function editar(Usuario $usuario);
	public function obtenerUsuarioPorEmail($email);
	public function comprarDisponibilidadNick($nick);
}