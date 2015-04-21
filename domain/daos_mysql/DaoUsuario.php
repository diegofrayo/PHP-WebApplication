<?php

namespace domain\daos_mysql;

use domain\classes\Usuario;
use domain\exceptions\DBTransactionException;
use domain\database\BDFactory;
use domain\idaos\IDaoUsuario;
use domain\dto\DTOCrud;

require_once '/../idaos/IDaoUsuario.php';
require_once '/../classes/Usuario.php';
require_once '/../database/BDFactory.php';
require_once '/../dto/DTOCrud.php';
require_once '/../exceptions/DBTransactionException.php';

class DaoUsuario implements IDaoUsuario 
{
	
	public function crear(Usuario $usuario) 
	{
		$manejadorBD = BDFactory::crearManejadorBD ();
		$consultaSQL = "insert into usuario (email, nombre, nick, password)
				" . " values (?,?,?,?)";
		$arrayDatos = array (
				$usuario->getEmail (),
				$usuario->getNombre (),
				$usuario->getNick (),
				$usuario->getPassword () 
		);
		$DTOConsulta = $manejadorBD->insertar ( $consultaSQL, $arrayDatos );
		
		if ($DTOConsulta->getExitoConsulta () == true) {
			return $usuario;
		}
		
		throw new DBTransactionException ();
	}
	
	public function editar(Usuario $usuario) 
	{
		$manejadorBD = BDFactory::crearManejadorBD ();
		$consultaSQL = "update usuario set nick=?, password=?,  nombre=? where email = ?";
		$arrayDatos = array (
				$usuario->getNick (),
				$usuario->getPassword (),
				$usuario->getNombre (),
				$usuario->getEmail () 
		);
		
		$exitoConsulta = $manejadorBD->editar ( $consultaSQL, $arrayDatos );
		
		if ($exitoConsulta == true) {
			return $usuario;
		}
		
		throw new DBTransactionException ();
	}
	
	public function obtenerUsuarioPorEmail($email) 
	{
		$manejadorBD = BDFactory::crearManejadorBD ();
		$consultaSQL = "select * from usuario where email = ?";
		$resultados = $manejadorBD->obtenerDatos ( $consultaSQL, array (
				$email 
		) );
		
		if (count ( $resultados ) == 1) {
			
			$nuevoUsuario = $resultados [0];
			$usuario = new Usuario ( $nuevoUsuario ['email'], $nuevoUsuario ['nick'], $nuevoUsuario ['nombre'], $nuevoUsuario ['password'] );
			
			return $usuario;
		}
		
		return null;
	}
	
	public function obtenerUsuarioPorNick($nick) 
	{
		$manejadorBD = BDFactory::crearManejadorBD ();
		$consultaSQL = "select * from usuario where nick = ?";
		$resultados = $manejadorBD->obtenerDatos ( $consultaSQL, array (
				$nick 
		) );
		
		if (count ( $resultados ) == 1) {
			
			$nuevoUsuario = $resultados [0];
			$usuario = new Usuario ( $nuevoUsuario ['email'], $nuevoUsuario ['nick'], $nuevoUsuario ['nombre'], $nuevoUsuario ['password'] );
			
			return $usuario;
		}
		
		return null;
	}
	
	public function comprarDisponibilidadNick($nick) 
	{
		$manejadorBD = BDFactory::crearManejadorBD ();
		$consultaSQL = "select * from usuario where nick = ? ";
		$resultados = $manejadorBD->obtenerDatos ( $consultaSQL, array (
				$nick 
		) );
		$numeroResultados = count ( $resultados );
		
		if ($numeroResultados == 0) {
			return true;
		}
		
		return false;
	}
	
}