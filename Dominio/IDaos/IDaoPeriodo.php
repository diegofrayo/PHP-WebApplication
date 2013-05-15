<?php

namespace Dominio\IDaos;

interface IDaoPeriodo
{
	public function crear(Periodo $periodo);
	public function borrar(Periodo $periodo);
	public function editar(Periodo $periodo);
	public function obtenerPeriodoPorId($id);
	public function obtenerListaDePeriodosDeUnUsuario(Usuario $usuario);
	
}