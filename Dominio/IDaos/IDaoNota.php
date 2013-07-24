<?php

namespace Dominio\IDaos;

use Dominio\Clases\Nota;
use Dominio\Clases\GrupoDeNotas;

interface IDaoNota
{
	public function crear(Nota $nota);
	public function borrar(Nota $nota);
	public function editar(Nota $nota);
	public function obtenerListaDeNotasDeUnGrupo(GrupoDeNotas $grupo);
	public function obtenerNotaPorId($id);

	/**
	 * Metodo para obtener las notas que todavia no se han realizado.
	 * @param unknown_type $fecha - Es la fecha actual
	*/
	public function obtenerNotasFuturas($fecha, $nickUsuario);
}