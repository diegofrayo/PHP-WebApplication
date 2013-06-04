<?php

namespace Dominio\IDaos;

use Dominio\Clases\Noticia;
use Dominio\Clases\Usuario;

interface IDaoNoticia
{
	public function crear(Noticia $noticia);
	public function borrar(Noticia $noticia);
	public function obtenerListaDeNoticiasDeUnUsuario(Usuario $usuario);
	
}