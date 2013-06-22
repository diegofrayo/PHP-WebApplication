<?php

namespace Dominio\Daos;

use Dominio\Clases\Noticia;
use Dominio\Clases\Usuario;
use Dominio\Excepciones\DBTransactionException;
use Dominio\Clases\Comentario;
use Dominio\BaseDeDatos\BDFactory;
use Dominio\IDaos\IDaoComentario;
use Dominio\DTO\DTOCrud;

require_once  $_SERVER['DOCUMENT_ROOT'].'/Dominio/IDaos/IDaoComentario.php';
require_once  $_SERVER['DOCUMENT_ROOT'].'/Dominio/Clases/Comentario.php';
require_once  $_SERVER['DOCUMENT_ROOT'].'/Dominio/BaseDeDatos/BDFactory.php';
require_once  $_SERVER['DOCUMENT_ROOT'].'/Dominio/DTO/DTOCrud.php';
require_once  $_SERVER['DOCUMENT_ROOT'].'/Dominio/Excepciones/DBTransactionException.php';

class DaoComentario implements IDaoComentario
{
	public function crear(Comentario $comentario)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "insert into comentario (id, texto,fecha,noticia,comentarista)
				" . " values (?,?,?,?,?)";
		$arrayDatos = array($comentario -> getId(),$comentario -> getTexto(), $comentario -> getFecha(), $comentario->getNoticia()->getId(), $comentario -> getUsuarioComentarista()->getEmail());
		$DTOConsulta = $manejadorBD -> insertar($consultaSQL, $arrayDatos);

		if ($DTOConsulta->getExitoConsulta() ==true){
			$comentario->setId($DTOConsulta->getUltimoId());
			return $comentario;
		}

		throw new DBTransactionException();
	}

	public function borrar(Comentario $comentario)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "delete from comentario where id = ? ";
		$exitoConsulta = $manejadorBD ->eliminar($consultaSQL, array($comentario->getId()));

		if ($exitoConsulta ==true){
			return $comentario;
		}

		throw new DBTransactionException();
	}

	public function obtenerListaDeComentariosDeUnaNoticia(Noticia $noticia)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "select * from comentario where noticia = ?";
		$resultados = $manejadorBD -> obtenerDatos($consultaSQL, array($noticia->getId()));
		$numeroResultados = count($resultados);
		$listaComentarios = array();

		if($numeroResultados!=0){
			$daoUsuario = new DaoUsuario();
			for ($i = 0; $i<$numeroResultados; $i++){
				$nuevoComentario = $resultados[$i];
				$comentarista = $daoUsuario->obtenerUsuarioPorEmail($nuevoComentario['comentarista']);
				$comentarioLeido = new Comentario($nuevoComentario['id'],$nuevoComentario['texto'],
						$nuevoComentario['fecha']);
				$comentarioLeido->setUsuarioComentarista($comentarista);
				$comentarioLeido->setNoticia($noticia);
				$listaComentarios[] = $comentarioLeido;
			}
			return $listaComentarios;
		}
		return null;
	}

	public function obtenerNumeroDeComentariosDeUnaNoticia(Noticia $noticia)
	{
		$listaComentarios = $this->obtenerListaDeComentariosDeUnaNoticia($noticia);
		if(is_null($listaComentario)){
			return 0;
		}
		return count($listaComentarios);
	}

}