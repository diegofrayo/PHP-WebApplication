<?php

namespace Dominio\IDaos;

use Dominio\Clases\Foto;

interface IDaoFoto
{
	public function crear(Foto $foto, $configuracion);
	public function borrar(Foto $foto);
	public function editar(Foto $foto);
	public function obtenerFotoPorId($id);
}