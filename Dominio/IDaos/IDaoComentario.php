<?php

namespace Dominio\IDaos;

use Dominio\Clases\Noticia;

interface IDaoComentario
{
	public function crear(Comentario $comentario);
	public function borrar(Comentario $comentario);
	public function obtenerListaDeComentariosDeUnaNoticia(Noticia $noticia);
	public function obtenerNumeroDeComentariosDeUnaNoticia(Noticia $noticia);
}