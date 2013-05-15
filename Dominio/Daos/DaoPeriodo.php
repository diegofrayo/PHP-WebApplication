<?php

namespace Dominio\Daos;

use Dominio\Excepciones\DBTransactionException;
use Dominio\Clases\Periodo;
use Dominio\BaseDeDatos\BDFactory;
use Dominio\IDaos\IDaoPeriodo;
use Dominio\DTO\DTOCrud;

require_once '/../IDaos/IDaoPeriodo.php';
require_once '/../Clases/Periodo.php';
require_once '/../BaseDeDatos/BDFactory.php';
require_once '/../DTO/DTOCrud.php';
require_once '/../Excepciones/DBTransactionException.php';

class DaoPeriodo implements IDaoPeriodo
{
	public function crear(Periodo $periodo)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "insert into periodo (id, fecha_inicio, fecha_final, descripcion, usuario)
				" . " values (?,?,?,?,?)";
		$arrayDatos = array($periodo -> getId(),$periodo -> getFechaInicio(), 
							$periodo -> getFechaFinal(), $periodo->getDescripcion(), 
							$periodo -> getUsuario()->getEmail());
		$DTOConsulta = $manejadorBD -> insertar($consultaSQL, $arrayDatos);

		if ($DTOConsulta->getExitoConsulta() ==true){
			$periodo->setId($DTOConsulta->getUltimoId());
			return $periodo;
		}

		throw new DBTransactionException();
	}

	public function borrar(Periodo $periodo)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "delete from periodo where id = ? ";
		$exitoConsulta = $manejadorBD ->eliminar($consultaSQL, array($periodo->getId()));

		if ($exitoConsulta ==true){
			return $periodo;
		}

		throw new DBTransactionException();
	}

	public function editar(Periodo $periodo)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "update periodo set fecha_inicio=?, fecha_final=?, descripcion=? where id = ?" ;
		$arrayDatos = array($periodo -> getFechaInicio(), 
							$periodo -> getFechaFinal(), 
							$periodo->getDescripcion(),$periodo -> getId());
		$exitoConsulta = $manejadorBD ->editar($consultaSQL, $arrayDatos );

		if ($exitoConsulta ==true){
			return $periodo;
		}

		throw new DBTransactionException();
	}
		
	public function obtenerPeriodoPorId($id)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "select * from periodo where id = ?";
		$resultados = $manejadorBD -> obtenerDatos($consultaSQL, array($id));

		if(count($resultados)==1){
			$nuevoPeriodo = $resultados[0];
			return new Periodo($nuevoPeriodo['id'],$nuevoPeriodo['fecha_inicio'],
					$nuevoPeriodo['fecha_final'],$nuevoPeriodo['descripcion']);
		}

		return null;
	}

	public function obtenerListaDePeriodosDeUnUsuario(Usuario $usuario)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "select * from periodo where usuario = ?";
		$resultados = $manejadorBD -> obtenerDatos($consultaSQL, array($usuario->getEmail()));
		$numeroResultados = count($resultados);
		$listaPeriodos= array();

		if($numeroResultados!=0){
			for ($i = 0; $i<$numeroResultados; $i++){
				$nuevoPeriodo = $resultados[$i];
				$periodoLeido = new Periodo($nuevoPeriodo['id'],$nuevoPeriodo['fecha_inicio'],
					$nuevoPeriodo['fecha_final'],$nuevoPeriodo['descripcion']);
				$periodoLeido->setUsuario($usuario);
				$listaPeriodos[] = $notificacionLeido;
			}
			return $listaPeriodos;
		}
		return null;
	}

}