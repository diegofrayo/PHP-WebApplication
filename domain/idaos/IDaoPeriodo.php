<?php

namespace domain\idaos;

use domain\classes\Usuario;
use domain\classes\Periodo;

interface IDaoPeriodo {
	public function crear(Periodo $periodo);
	public function borrar(Periodo $periodo);
	public function editar(Periodo $periodo);
	public function obtenerPeriodoPorId($id);
	public function obtenerListaDePeriodosDeUnUsuario(Usuario $usuario);
	public function comprobarSiUnPeriodoPerteneceAUnUsuario($idPeriodo, $emailUsuario);
}