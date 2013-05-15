<?php

namespace Dominio\IDaos;

use Dominio\Clases\GrupoDeNotas;

interface IDaoNota
{
	public function crear(Nota $nota);
	public function borrar(Nota $nota);
	public function editar(Nota $nota);
	public function obtenerListaDeNotasDeUnGrupo(GrupoDeNotas $grupo);
	public function obtenerNotaPorId($id);
}