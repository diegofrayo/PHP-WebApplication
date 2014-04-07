<?php

namespace domain\idaos;

use domain\classes\Nota;
use domain\classes\GrupoDeNotas;

interface IDaoNota {
	public function crear(Nota $nota);
	public function borrar(Nota $nota);
	public function editar(Nota $nota);
	public function obtenerListaDeNotasDeUnGrupo(GrupoDeNotas $grupo);
	public function obtenerNotaPorId($id);
	
	/**
	 * Metodo para obtener las notas que todavia no se han realizado.
	 */
	public function obtenerNotasFuturas($nickUsuario);
}