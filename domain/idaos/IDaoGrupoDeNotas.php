<?php

namespace domain\idaos;

use domain\classes\GrupoDeNotas;
use domain\classes\Asignatura;

interface IDaoGrupoDeNotas {
	public function crear(GrupoDeNotas $grupoDeNotas);
	public function borrar(GrupoDeNotas $grupoDeNotas);
	public function editar(GrupoDeNotas $grupoDeNotas);
	public function obtenerGrupoDefectoDeUnaAsignatura(Asignatura $asignatura);
	public function obtenerListaDeGruposDeUnaAsignatura(Asignatura $asignatura);
	public function obtenerGrupoPorId($id);
}