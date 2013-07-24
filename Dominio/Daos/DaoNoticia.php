<?php

namespace Dominio\Daos;

use Dominio\Clases\Usuario;

use Dominio\Excepciones\DBTransactionException;
use Dominio\Clases\Noticia;
use Dominio\BaseDeDatos\BDFactory;
use Dominio\IDaos\IDaoNoticia;
use Dominio\DTO\DTOCrud;

$_SERVER['DOCUMENT_ROOT'] = 'C:/xampp/htdocs/Qualify';

require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/IDaos/IDaoNoticia.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Clases/Noticia.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/BaseDeDatos/BDFactory.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/DTO/DTOCrud.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Excepciones/DBTransactionException.php';

class DaoNoticia implements IDaoNoticia
{
	public function crear(Noticia $noticia)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "insert into noticia (id, usuario, nota)
				" . " values (?,?,?)";
		$arrayDatos = array($noticia -> getId(),$noticia -> getUsuario(), $noticia -> getUsuario());
		$DTOConsulta = $manejadorBD -> insertar($consultaSQL, $arrayDatos);

		if ($DTOConsulta->getExitoConsulta() ==true){
			$noticia->setId($DTOConsulta->getUltimoId());
			return $noticia;
		}

		throw new DBTransactionException();
	}

	public function borrar(Noticia $noticia)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "delete from noticia where id = ? ";
		$exitoConsulta = $manejadorBD ->eliminar($consultaSQL, array($noticia->getId()));

		if ($exitoConsulta ==true){
			return $noticia;
		}

		throw new DBTransactionException();
	}

	public function obtenerListaDeNoticiasDeUnUsuario(Usuario $usuario)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "select * from noticia where usuario = ?";
		$resultados = $manejadorBD -> obtenerDatos($consultaSQL, array($usuario->getEmail()));
		$numeroResultados = count($resultados);
		$listaNoticia = array();

		if($numeroResultados!=0){
			$daoNotas = new DaoNota();
			for ($i = 0; $i<$numeroResultados; $i++){
				$nuevaNoticia = $resultados[$i];
				$noticiaLeida = new Noticia($nuevaNoticia['id']);
				$noticiaLeida->setNota($daoNotas->obtenerNotaPorId($nuevaNoticia['nota']));
				$noticiaLeida->setUsuario($usuario);
				$listaNoticia[] = $noticiaLeida;
			}
			return $listaNoticia;
		}

		return null;
	}
}