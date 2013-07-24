<?php

namespace Dominio\Daos;

use Dominio\Clases\Usuario;

use Dominio\Excepciones\DBTransactionException;
use Dominio\Clases\RelacionEntreUsuarios;
use Dominio\BaseDeDatos\BDFactory;
use Dominio\IDaos\IDaoRelacionEntreUsuarios;
use Dominio\DTO\DTOCrud;

$_SERVER['DOCUMENT_ROOT'] = 'C:/xampp/htdocs/Qualify';

require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/IDaos/IDaoRelacionEntreUsuarios.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Clases/RelacionEntreUsuarios.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/BaseDeDatos/BDFactory.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/DTO/DTOCrud.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Excepciones/DBTransactionException.php';

class DaoRelacionEntreUsuarios implements IDaoRelacionEntreUsuarios
{
	public function crear(RelacionEntreUsuarios $relacionEntreUsuarios)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "insert into relaciones (id, usuario_receptor, usuario_emisor, estado)
				" . " values (?,?,?,?,?)";
		$arrayDatos = array($relacionEntreUsuarios -> getId(),$relacionEntreUsuarios -> getUsuarioReceptor()->getEmail(),
				$relacionEntreUsuarios -> getUsuarioEmisor()->getEmail(),
				$relacionEntreUsuarios->getEstado());
		$DTOConsulta = $manejadorBD -> insertar($consultaSQL, $arrayDatos);

		if ($DTOConsulta->getExitoConsulta() ==true){
			$relacionEntreUsuarios->setId($DTOConsulta->getUltimoId());
			return $relacionEntreUsuarios;
		}

		throw new DBTransactionException();
	}

	public function borrar(RelacionEntreUsuarios $relacionEntreUsuarios)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "delete from relaciones where id = ? ";
		$exitoConsulta = $manejadorBD ->eliminar($consultaSQL, array($relacionEntreUsuarios->getId()));

		if ($exitoConsulta ==true){
			return $relacionEntreUsuarios;
		}

		throw new DBTransactionException();
	}

	public function editar(RelacionEntreUsuarios $relacionEntreUsuarios)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "update relaciones set estado=? where id = ?" ;
		$arrayDatos = array($relacionEntreUsuarios->getEstado(),$relacionEntreUsuarios -> getId());
		$exitoConsulta = $manejadorBD ->editar($consultaSQL, $arrayDatos );

		if ($exitoConsulta ==true){
			return $relacionEntreUsuarios;
		}

		throw new DBTransactionException();
	}

	public function obtenerSolicitudesDeAmistad(Usuario $usuario)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "select * from relaciones where usuario_receptor = ? and estado = ?";
		$resultados = $manejadorBD -> obtenerDatos($consultaSQL, array($usuario->getEmail(), false));
		$numeroResultados = count($resultados);
		$listaRelacionEntreUsuarios = array();

		if($numeroResultados!=0){
			for ($i = 0; $i<$numeroResultados; $i++){
				$nuevaRelacionEntreUsuarios = $resultados[$i];
				$relacionEntreUsuariosLeida = new RelacionEntreUsuarios($nuevaRelacionEntreUsuarios['id'],
						$nuevaRelacionEntreUsuarios['estado']);
				$listaRelacionEntreUsuarios[] = $relacionEntreUsuariosLeida;
			}
			return $listaRelacionEntreUsuarios;
		}
		return null;
	}

	public function obtenerAmigosDeUnUsuario(Usuario $usuario)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "select * from relaciones where usuario_emisor = ? and estado = ?";
		$resultados = $manejadorBD -> obtenerDatos($consultaSQL, array($usuario->getEmail(),true));
		$numeroResultados = count($resultados);
		$listaRelacionEntreUsuarios = array();
		$listaDeAmigos = array();
		$daoUsuario = new DaoUsuario();
		if($numeroResultados!=0){
			for ($i = 0; $i<$numeroResultados; $i++){
				$nuevaRelacionEntreUsuarios = $resultados[$i];
				$amigoLeido = $daoUsuario->obtenerUsuarioPorEmail($nuevaRelacionEntreUsuarios['usuario_receptor']);
				$listaDeAmigos[] = $amigoLeido;
			}
		}

		$consultaSQL = "select * from relaciones where usuario_receptor = ? and estado = ?";
		$resultados = $manejadorBD -> obtenerDatos($consultaSQL, array($usuario->getEmail(),1));
		$numeroResultados = count($resultados);

		if($numeroResultados!=0){
			for ($i = 0; $i<$numeroResultados; $i++){
				$nuevaRelacionEntreUsuarios = $resultados[$i];
				$amigoLeido = $daoUsuario->obtenerUsuarioPorEmail($nuevaRelacionEntreUsuarios['usuario_emisor']);
				$listaDeAmigos[] = $amigoLeido;
			}
		}
		return $listaDeAmigos;
	}

	public function comprobarAmistad(RelacionEntreUsuarios $relacion){
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "select * from relaciones where usuario_emisor = ? and usuario_receptor = ? and estado = ?";
		$arrayDatos = array($relacion->getUsuarioEmisor(), $relacion->getUsuarioReceptor(), true);
		$resultados = $manejadorBD -> obtenerDatos($consultaSQL,$arrayDatos);
		$numeroResultados = count($resultados);

		if($numeroResultados!=0){
			return true;
		}

		$arrayDatos = array($relacion->getUsuarioReceptor(),$relacion->getUsuarioEmisor(), true);
		$resultados = $manejadorBD -> obtenerDatos($consultaSQL,$arrayDatos);
		$numeroResultados = count($resultados);

		if($numeroResultados!=0){
			return true;
		}

		return false;
	}
}