<?php

namespace domain\daos_mysql;

use domain\classes\Usuario;
use domain\exceptions\DBTransactionException;
use domain\classes\Periodo;
use domain\database\BDFactory;
use domain\idaos\IDaoPeriodo;
use domain\dto\DTOCrud;

require_once '/../idaos/IDaoPeriodo.php';
require_once '/../classes/Periodo.php';
require_once '/../database/BDFactory.php';
require_once '/../dto/DTOCrud.php';
require_once '/../exceptions/DBTransactionException.php';

class DaoPeriodo implements IDaoPeriodo 
{
	
	public function crear(Periodo $periodo) 
	{
		$manejadorBD = BDFactory::crearManejadorBD ();
		$consultaSQL = "insert into periodo (id, fecha_inicio, fecha_final,usuario, nombre)" . " values (?,?,?,?,?)";
		
		$arrayDatos = array (
				0,
				date ( 'Y-m-d', strtotime ( $periodo->getFechaInicio () ) ),
				date ( 'Y-m-d', strtotime ( $periodo->getFechaFinal () ) ),
				$periodo->getUsuario ()->getEmail (),
				$periodo->getNombre () 
		);
		$DTOConsulta = $manejadorBD->insertar ( $consultaSQL, $arrayDatos );
		
		if ($DTOConsulta->getExitoConsulta () == true) {
			
			$periodo->setId ( $DTOConsulta->getUltimoId () );
			return $periodo;
		}
		
		throw new DBTransactionException ();
	}
	
	public function borrar(Periodo $periodo) 
	{
		$manejadorBD = BDFactory::crearManejadorBD ();
		$consultaSQL = "delete from periodo where id = ? ";
		$exitoConsulta = $manejadorBD->eliminar ( $consultaSQL, array (
				$periodo->getId () 
		) );
		
		if ($exitoConsulta == true) {
			return $periodo;
		}
		
		throw new DBTransactionException ();
	}
	
	public function editar(Periodo $periodo) 
	{
		$manejadorBD = BDFactory::crearManejadorBD ();
		$consultaSQL = "update periodo set fecha_inicio=?, fecha_final=?, nombre=? where id = ?";
		$arrayDatos = array (
				$periodo->getFechaInicio (),
				$periodo->getFechaFinal (),
				$periodo->getNombre (),
				$periodo->getId () 
		);
		$exitoConsulta = $manejadorBD->editar ( $consultaSQL, $arrayDatos );
		
		if ($exitoConsulta == true) {
			return $periodo;
		}
		
		throw new DBTransactionException ();
	}
	
	public function obtenerPeriodoPorId($id) 
	{
		$manejadorBD = BDFactory::crearManejadorBD ();
		$consultaSQL = "select * from periodo where id = ?";
		$resultados = $manejadorBD->obtenerDatos ( $consultaSQL, array (
				$id 
		) );
		
		if (count ( $resultados ) == 1) {
			
			$nuevoPeriodo = $resultados [0];
			return new Periodo ( $nuevoPeriodo ['id'], $nuevoPeriodo ['fecha_inicio'], $nuevoPeriodo ['fecha_final'], $nuevoPeriodo ['nombre'] );
		}
		
		return null;
	}
	
	public function obtenerListaDePeriodosDeUnUsuario(Usuario $usuario) 
	{
		$manejadorBD = BDFactory::crearManejadorBD ();
		$consultaSQL = "select * from periodo where usuario = ?";
		$resultados = $manejadorBD->obtenerDatos ( $consultaSQL, array (
				$usuario->getEmail () 
		) );
		$numeroResultados = count ( $resultados );
		$listaPeriodos = array ();
		
		if ($numeroResultados != 0) {
			
			for($i = 0; $i < $numeroResultados; $i ++) {
				
				$nuevoPeriodo = $resultados [$i];
				$periodoLeido = new Periodo ( $nuevoPeriodo ['id'], date ( "d-m-Y", strtotime ( $nuevoPeriodo ['fecha_inicio'] ) ), date ( "d-m-Y", strtotime ( $nuevoPeriodo ['fecha_final'] ) ), $nuevoPeriodo ['nombre'] );
				$periodoLeido->setUsuario ( $usuario );
				$listaPeriodos [] = $periodoLeido;
			}
		}
		
		return $listaPeriodos;
	}
	
	public function comprobarSiUnPeriodoPerteneceAUnUsuario($idPeriodo, $emailUsuario) 
	{
		$manejadorBD = BDFactory::crearManejadorBD ();
		$consultaSQL = "select * from periodo where usuario = ? and id = ?";
		$resultados = $manejadorBD->obtenerDatos ( $consultaSQL, array (
				$emailUsuario,
				$idPeriodo 
		) );
		
		if (count ( $resultados ) == 1) {
			
			return true;
		}
		
		return false;
	}
	
}