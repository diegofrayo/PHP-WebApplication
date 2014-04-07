<?php

namespace domain\idaos;

use domain\classes\Periodo;
use domain\classes\Asignatura;

interface IDaoAsignatura {
	public function crear(Asignatura $asignatura);
	public function borrar(Asignatura $asignatura);
	public function editar(Asignatura $asignatura);
	public function obtenerAsignaturaPorId($id);
	public function obtenerListaDeAsignaturasDeUnPeriodo(Periodo $periodo);
}