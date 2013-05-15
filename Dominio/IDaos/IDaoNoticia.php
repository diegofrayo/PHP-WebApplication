<?php

namespace Dominio\IDaos;

interface IDaoNoticia
{
	public function crear(Noticia $noticia);
	public function borrar(Noticia $noticia);
	public function obtenerListaDeNoticiasDeUnUsuario(Usuario $usuario);
	
}