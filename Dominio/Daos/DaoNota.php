<?php

namespace Dominio\Daos;

use Dominio\Clases\GrupoDeNotas;
use Dominio\Excepciones\DBTransactionException;
use Dominio\Clases\Nota;
use Dominio\BaseDeDatos\BDFactory;
use Dominio\IDaos\IDaoNota;
use Dominio\DTO\DTOCrud;

require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/IDaos/IDaoNota.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Clases/Nota.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/BaseDeDatos/BDFactory.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/DTO/DTOCrud.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Excepciones/DBTransactionException.php';

class DaoNota implements IDaoNota
{
	public function crear(Nota $nota)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "insert into nota (id, nombre, valor, porcentaje, grupo, fecha)
				" . " values (?,?,?,?,?,?)";
		$arrayDatos = array($nota -> getId(),$nota -> getNombre(), $nota -> getValor(), $nota->getPorcentaje(), $nota -> getGrupo()->getId(), $nota->getFecha());
		$DTOConsulta = $manejadorBD -> insertar($consultaSQL, $arrayDatos);

		if ($DTOConsulta->getExitoConsulta() ==true){
			$nota->setId($DTOConsulta->getUltimoId());
			return $nota;
		}

		throw new DBTransactionException();
	}

	public function borrar(Nota $nota)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "delete from nota where id = ? ";
		$exitoConsulta = $manejadorBD ->eliminar($consultaSQL, array($nota->getId()));

		if ($exitoConsulta ==true){
			return $nota;
		}

		throw new DBTransactionException();
	}

	public function editar(Nota $nota)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "update nota set nombre=?, valor=?, porcentaje=?, fecha=? where id = ?" ;
		$arrayDatos = array($nota -> getNombre(), $nota -> getValor(), $nota->getPorcentaje(), $nota->getFecha(),$nota -> getId());
		$exitoConsulta = $manejadorBD ->editar($consultaSQL, $arrayDatos );

		if ($exitoConsulta ==true){
			return $nota;
		}

		throw new DBTransactionException();
	}

	public function obtenerListaDeNotasDeUnGrupo(GrupoDeNotas $grupo)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "select * from nota where grupo = ?";
		$resultados = $manejadorBD -> obtenerDatos($consultaSQL, array($grupo->getId()));
		$numeroResultados = count($resultados);
		$listaNotas = array();

		if($numeroResultados!=0){
			for ($i = 0; $i<$numeroResultados; $i++){
				$nuevaNota = $resultados[$i];
				$notaLeida = new Nota($nuevaNota['id'],$nuevaNota['nombre'],
						$nuevaNota['valor'],$nuevaNota['porcentaje'] ,$nuevaNota['fecha']);
				$notaLeida->setGrupo($grupo);
				$listaNotas[] = $notaLeida;
			}
		}

		return $listaNotas;
	}

	public function obtenerNotaPorId($id){

		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "select * from nota where id = ?";
		$resultados = $manejadorBD -> obtenerDatos($consultaSQL, array($id));

		if(count($resultados)==1){
			$daoGrupo = new DaoGrupoDeNotas();
			$nuevaNota = $resultados[0];
			$notaLeida = new Nota($nuevaNota['id'],$nuevaNota['nombre'],
					$nuevaNota['valor'], $nuevaNota['porcentaje'] ,$nuevaNota['fecha']);
			$notaLeida->setGrupo($daoGrupo->obtenerGrupoPorId($nuevaNota['grupo']));
			return $notaLeida;
		}

		return null;

	}

	public function obtenerNotasFuturas($fecha)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "select * from nota where fecha  >= ?";
		$resultados = $manejadorBD -> obtenerDatos($consultaSQL, array($fecha));
		$numeroResultados = count($resultados);
		$listaNotas = array();

		if($numeroResultados!=0){
			$daoGrupo = new DaoGrupoDeNotas();
			for ($i = 0; $i<$numeroResultados; $i++){
				$nuevaNota = $resultados[$i];
				$notaLeida = new Nota($nuevaNota['id'],$nuevaNota['nombre'],
						$nuevaNota['valor'],$nuevaNota['porcentaje'] ,$nuevaNota['fecha']);
				$notaLeida->setGrupo($daoGrupo->obtenerGrupoPorId($nuevaNota['grupo']));
				$listaNotas[] = $notaLeida;
			}
		}

		return $listaNotas;
	}

}