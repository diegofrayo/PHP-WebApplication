<?php

namespace Dominio\Daos;

use Dominio\Excepciones\DBTransactionException;
use Dominio\Clases\Usuario;
use Dominio\BaseDeDatos\BDFactory;
use Dominio\IDaos\IDaoUsuario;
use Dominio\DTO\DTOCrud;

require_once '/../IDaos/IDaoUsuario.php';
require_once '/../Clases/Usuario.php';
require_once '/../BaseDeDatos/BDFactory.php';
require_once '/../DTO/DTOCrud.php';
require_once '/../Excepciones/DBTransactionException.php';

class DaoUsuario implements IDaoUsuario
{

	public function crear(Usuario $usuario)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "insert into usuario (email, nombre, nick, password, foto)
				" . " values (?,?,?,?,?)";
		$arrayDatos = array($usuario -> getEmail(), $usuario->getNombre(), $usuario -> getNick(), $usuario->getPassword(), $usuario -> getFoto());
		var_dump($arrayDatos);
		$DTOConsulta = $manejadorBD -> insertar($consultaSQL, $arrayDatos);

		if ($DTOConsulta->getExitoConsulta() ==true){
			return $usuario;
		}

		throw new DBTransactionException();
	}

	public function editar(Usuario $usuario)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "update usuario set nick=?, password=?, foto=?, nombre=? where email = ?" ;
		$arrayDatos = array($usuario -> getEmail(), $usuario -> getNick(), $usuario->getPassword(), $usuario -> getFoto(), $usuario->getNombre());

		$exitoConsulta = $manejadorBD ->editar($consultaSQL, $arrayDatos);

		if ($exitoConsulta ==true){
			return $usuario;
		}

		throw new DBTransactionException();
	}

	public function obtenerUsuarioPorEmail($email)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "select * from usuario where email = ?";
		$resultados = $manejadorBD -> obtenerDatos($consultaSQL, array($email));

		if(count($resultados)==1){
			$nuevoUsuario = $resultados[0];
			return new Usuario($nuevoUsuario['email'],$nuevoUsuario['nick'],
					$nuevoUsuario['nombre'],$nuevoUsuario['password'],$nuevoUsuario['foto']);
		}

		return null;
	}

	public function buscarUsuariosPorNombre($nombre)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "select * from usuario where nombre LIKE '%?%'";
		$resultados = $manejadorBD -> obtenerDatos($consultaSQL, array($nombre));
		$numeroResultados = count($resultados);
		$listaUsuario = array();

		if($numeroResultados!=0){
			for ($i = 0; $i<$numeroResultados; $i++){
				$nuevoUsuario = $resultados[$i];
				$usuarioLeido = new Usuario($nuevoUsuario['email'],$nuevoUsuario['nick'],
						$nuevoUsuario['nombre'],$nuevoUsuario['password'],$nuevoUsuario['foto']);
				$listaUsuario[] = $usuarioLeido;
			}
			return $listaUsuario;
		}

		return null;
	}

	public function recuperarPassword(Usuario $usuario)
	{

	}

	public function comprarDisponibilidadNick($nick)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "select * from usuario where nick = ? ";
		$resultados = $manejadorBD -> obtenerDatos($consultaSQL, array($nick));
		$numeroResultados = count($resultados);
			
		if($numeroResultados==0){
			return true;
		}

		return false;
	}

}