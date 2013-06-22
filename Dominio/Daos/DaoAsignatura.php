<?php

namespace Dominio\Daos;

use Dominio\Clases\Periodo;
use Dominio\Excepciones\DBTransactionException;
use Dominio\Clases\Asignatura;
use Dominio\BaseDeDatos\BDFactory;
use Dominio\IDaos\IDaoAsignatura;
use Dominio\DTO\DTOCrud;

require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/IDaos/IDaoAsignatura.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Clases/Asignatura.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/BaseDeDatos/BDFactory.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/DTO/DTOCrud.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Excepciones/DBTransactionException.php';

class DaoAsignatura implements IDaoAsignatura
{
	public function crear(Asignatura $asignatura)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "insert into asignatura (id, nombre,periodo)
				" . " values (?,?,?)";
		$arrayDatos = array(0,$asignatura -> getNombre(), $asignatura->getPeriodo()->getId());
		$DTOConsulta = $manejadorBD -> insertar($consultaSQL, $arrayDatos);

		if ($DTOConsulta->getExitoConsulta() ==true){
			$asignatura->setId($DTOConsulta->getUltimoId());
			return $asignatura;
		}

		throw new DBTransactionException();
	}

	public function borrar(Asignatura $asignatura)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "delete from asignatura where id = ? ";
		$exitoConsulta = $manejadorBD ->eliminar($consultaSQL, array($asignatura->getId()));

		if ($exitoConsulta == true){
			return $asignatura;
		}

		throw new DBTransactionException();

	}

	public function editar(Asignatura $asignatura)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "update asignatura set nombre=?, periodo = ? where id = ?" ;
		$arrayDatos = array($asignatura -> getNombre(),$asignatura->getPeriodo()->getId(), $asignatura -> getId());
		$exitoConsulta = $manejadorBD ->editar($consultaSQL, $arrayDatos );

		if ($exitoConsulta ==true){
			return $asignatura;
		}

		throw new DBTransactionException();
	}

	public function obtenerListaDeAsignaturasDeUnPeriodo(Periodo $periodo)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "select * from asignatura where periodo = ?";
		$resultados = $manejadorBD -> obtenerDatos($consultaSQL, array($periodo->getId()));
		$numeroResultados = count($resultados);
		$listaAsignatura = array();

		if($numeroResultados!=0){
			for ($i = 0; $i<$numeroResultados; $i++){
				$nuevaAsignatura = $resultados[$i];
				$asignaturaLeida = new Asignatura($nuevaAsignatura['id'],$nuevaAsignatura['nombre']);
				$asignaturaLeida->setPeriodo($periodo);
				$listaAsignatura[] = $asignaturaLeida;
			}

		}
		return $listaAsignatura;
	}

	public function obtenerAsignaturaPorId($id)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "select * from asignatura where id = ?";
		$resultados = $manejadorBD -> obtenerDatos($consultaSQL, array($id));

		if(count($resultados)==1){
			$nuevaAsignatura = $resultados[0];
			return new Asignatura($nuevaAsignatura['id'],$nuevaAsignatura['nombre']);
		}

		return null;
	}

}
