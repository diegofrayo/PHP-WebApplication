<?php

namespace domain\daos_mysql;

use domain\classes\GrupoDeNotas;
use domain\exceptions\DBTransactionException;
use domain\classes\Nota;
use domain\database\BDFactory;
use domain\idaos\IDaoNota;
use domain\dto\DTOCrud;

require_once '/../idaos/IDaoNota.php';
require_once '/../classes/Nota.php';
require_once '/../dto/DTOCrud.php';
require_once '/../exceptions/DBTransactionException.php';

class DaoNota implements IDaoNota {
	
	public function crear(Nota $nota) 
	{
		$manejadorBD = BDFactory::crearManejadorBD ();
		$consultaSQL = "insert into nota (id, nombre, valor, porcentaje, grupo, fecha)
				" . " values (?,?,?,?,?,?)";
		$arrayDatos = array (
				$nota->getId (),
				$nota->getNombre (),
				$nota->getValor (),
				$nota->getPorcentaje (),
				$nota->getGrupo ()->getId (),
				$nota->getFecha () 
		);
		$DTOConsulta = $manejadorBD->insertar ( $consultaSQL, $arrayDatos );
		
		if ($DTOConsulta->getExitoConsulta () == true) {
			
			$nota->setId ( $DTOConsulta->getUltimoId () );
			return $nota;
		}
		
		throw new DBTransactionException ();
	}
	
	public function borrar(Nota $nota) 
	{
		$manejadorBD = BDFactory::crearManejadorBD ();
		$consultaSQL = "delete from nota where id = ? ";
		$exitoConsulta = $manejadorBD->eliminar ( $consultaSQL, array (
				$nota->getId () 
		) );
		
		if ($exitoConsulta == true) {
			return $nota;
		}
		
		throw new DBTransactionException ();
	}
	
	public function editar(Nota $nota) 
	{
		$manejadorBD = BDFactory::crearManejadorBD ();
		$consultaSQL = "update nota set nombre=?, grupo=?, valor=?, porcentaje=?, fecha=? where id = ?";
		$arrayDatos = array (
				$nota->getNombre (),
				$nota->getGrupo ()->getId (),
				$nota->getValor (),
				$nota->getPorcentaje (),
				$nota->getFecha (),
				$nota->getId () 
		);
		$exitoConsulta = $manejadorBD->editar ( $consultaSQL, $arrayDatos );
		
		if ($exitoConsulta == true) {
			return $nota;
		}
		
		throw new DBTransactionException ();
	}
	
	public function obtenerListaDeNotasDeUnGrupo(GrupoDeNotas $grupo) 
	{
		$manejadorBD = BDFactory::crearManejadorBD ();
		$consultaSQL = "select * from nota where grupo = ? order by fecha";
		$resultados = $manejadorBD->obtenerDatos ( $consultaSQL, array (
				$grupo->getId () 
		) );
		$numeroResultados = count ( $resultados );
		$listaNotas = array ();
		
		if ($numeroResultados != 0) {
			
			for($i = 0; $i < $numeroResultados; $i ++) {
				
				$nuevaNota = $resultados [$i];
				$notaLeida = new Nota ( $nuevaNota ['id'], $nuevaNota ['nombre'], $nuevaNota ['valor'], $nuevaNota ['porcentaje'], $nuevaNota ['fecha'] );
				$notaLeida->setGrupo ( $grupo );
				$listaNotas [] = $notaLeida;
			}
		}
		
		return $listaNotas;
	}
	
	public function obtenerNotaPorId($id) 
	{
		$manejadorBD = BDFactory::crearManejadorBD ();
		$consultaSQL = "select * from nota where id = ?";
		$resultados = $manejadorBD->obtenerDatos ( $consultaSQL, array (
				$id 
		) );
		
		if (count ( $resultados ) == 1) {
			
			$daoGrupo = new DaoGrupoDeNotas ();
			$nuevaNota = $resultados [0];
			$notaLeida = new Nota ( $nuevaNota ['id'], $nuevaNota ['nombre'], $nuevaNota ['valor'], $nuevaNota ['porcentaje'], $nuevaNota ['fecha'] );
			$notaLeida->setGrupo ( $daoGrupo->obtenerGrupoPorId ( $nuevaNota ['grupo'] ) );
			return $notaLeida;
		}
		
		return null;
	}
	public function obtenerNotasFuturas($emailUsuario) {
		
		$manejadorBD = BDFactory::crearManejadorBD ();
		
		$consultaSQL = "select distinct nota.id,nota.nombre,nota.valor,nota.porcentaje,nota.fecha,nota.grupo " 
				. "from nota,grupo_de_notas,asignatura,periodo,usuario where periodo.usuario = ? and " 
						. "periodo.id = asignatura.periodo and asignatura.id = grupo_de_notas.asignatura and " 
								. "grupo_de_notas.id = nota.grupo and nota.fecha >= ? order by fecha";
		$resultados = $manejadorBD->obtenerDatos ( $consultaSQL, array (
				$emailUsuario,
				Date('Y-m-d')
		) );
		
		$numeroResultados = count ( $resultados );
		$listaNotas = array ();
		
		if ($numeroResultados != 0) {
			
			$daoGrupo = new DaoGrupoDeNotas ();
			for($i = 0; $i < $numeroResultados; $i ++) {
				
				$nuevaNota = $resultados [$i];
				$notaLeida = new Nota ( $nuevaNota ['id'], $nuevaNota ['nombre'], $nuevaNota ['valor'], $nuevaNota ['porcentaje'], $nuevaNota ['fecha'] );
				$notaLeida->setGrupo ( $daoGrupo->obtenerGrupoPorId ( $nuevaNota ['grupo'] ) );
				$listaNotas [] = $notaLeida;
			}
		}
		
		return $listaNotas;
	}
	
}