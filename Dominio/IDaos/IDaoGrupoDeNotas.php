<?php

namespace Dominio\IDaos;

use Dominio\Clases\GrupoDeNotas;

use Dominio\Clases\Asignatura;

interface IDaoGrupoDeNotas
{
	public function crear(GrupoDeNotas $grupoDeNotas);
	public function borrar(GrupoDeNotas $grupoDeNotas);
	public function editar(GrupoDeNotas $grupoDeNotas);
	public function obtenerGrupoDefectoDeUnaAsignatura(Asignatura $asignatura);
	public function obtenerGrupoPorId($id);
}