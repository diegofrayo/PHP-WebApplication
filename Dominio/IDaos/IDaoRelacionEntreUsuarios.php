<?php

namespace Dominio\IDaos;

interface IDaoRelacionEntreUsuarios
{
	public function crear(RelacionEntreUsuarios $relacion);
	public function borrar(RelacionEntreUsuarios $relacion);
	public function editar(RelacionEntreUsuarios $relacion);
	public function obtenerSolicitudesDeAmistad(Usuario $usuario);
	public function obtenerAmigosDeUnUsuario(Usuario $usuario);
	public function comprobarAmistad(RelacionEntreUsuarios $relacion);
	
}