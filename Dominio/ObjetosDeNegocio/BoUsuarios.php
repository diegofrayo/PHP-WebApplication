<?php

/**
 * Esta clase es un objeto de negocio.
 * Va a manejar toda la logica de las siguientes entemailades:
 * Usuario. Va a servir para hacer el login, registro, y busqueda
 */

namespace Dominio\ObjetosDeNegocio;

use Dominio\Clases\Foto;

use Dominio\Daos\DaoFoto;

require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Daos/DaoUsuario.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Excepciones/BusinessLogicException.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Daos/DaoFoto.php';

use Dominio\Excepciones\BusinessLogicException;
use Dominio\Clases\Usuario;
use Dominio\Daos\DaoUsuario;


class BoUsuarios
{
	private $_usuarioDao;
	private $_fotoDao;

	public function __construct()
	{
		$this->_usuarioDao = new DaoUsuario();
		$this->_fotoDao = new DaoFoto();
	}

	/**
	 * Metodo para crear un usuario
	 * @param Usuario $usuario
	 * @throws BusinessLogicException
	 * @return Usuario
	 */
	public function registrarUsuario(Usuario $usuario)
	{
		if($this->obtenerUsuarioPorEmail($usuario->getEmail()) == null){
			if($this->comprarDisponibilidadNick($usuario->getNick()) == true){
				return $this->_usuarioDao->crear($usuario);
			}
			throw new BusinessLogicException("El nick no esta disponible");
		}
		throw new BusinessLogicException("El email ya esta registrado en la base de datos");
	}

	/**
	 * Metodo para editar un usuario
	 * @param Usuario $usuario
	 * @throws BusinessLogicException
	 * @return Usuario
	 */
	public function editarUsuario(Usuario $usuario)
	{
		$usuarioObtenido = $this->obtenerUsuarioPorEmail($usuario->getEmail());
		//Si el usuario ingresado existe
		if($usuarioObtenido !=null){
			//Si tienen los mismos nicks
			if(strcmp(strtolower($usuario->getNick()) ,strtolower($usuarioObtenido->getNick()) ) == 0){
				return $this->_usuarioDao->editar($usuario);
			}else{
				if($this->comprarDisponibilidadNick($usuario->getNick()) == true){
					return $this->_usuarioDao->editar($usuario);
				}
				throw new BusinessLogicException("El nick nuevo no se encuentra disponible");
			}
		}
		throw new BusinessLogicException("El usuario que quiere editar no existe");
	}

	/**
	 * Metodo para obtener un usuario por el email
	 * @param unknown_type $email
	 * @return Ambigous <NULL, \Dominio\Clases\Usuario>
	 */
	public function obtenerUsuarioPorEmail($email)
	{
		return $this->_usuarioDao->obtenerUsuarioPorEmail($email);
	}

	/**
	 * Metodo para comprobar si un nick esta disponible
	 * @param unknown_type $nick
	 * @return boolean
	 */
	public function comprarDisponibilidadNick($nick)
	{
		return $this->_usuarioDao->comprarDisponibilidadNick($nick);
	}

	/**
	 * Metodo para buscar usuarios por nombre
	 * @param unknown_type $nombre
	 */
	public function buscarUsuariosPorNombre($nombre)
	{
		return $this->_usuarioDao->buscarUsuariosPorNombre($nombre);
	}

	/**
	 * Metodo para iniciar sesion
	 * @param unknown_type $email
	 * @param unknown_type $password
	 * @throws BusinessLogicException
	 * @return \Dominio\ObjetosDeNegocio\Ambigous
	 */
	public function iniciarSesionUsuario($email, $password)
	{
		$usuarioBD = $this->obtenerUsuarioPorEmail($email);

		if($usuarioBD !=null){
			if($usuarioBD->getPassword() == $password){
				return $usuarioBD;
			}
			throw new BusinessLogicException("El password ingresado es incorrecto");
		}
		throw new BusinessLogicException("El email ingresado no existe");

	}

	public function crearFoto(Foto $foto, $configuracion)
	{
		if($foto !=null){
			return $this->_fotoDao->crear($foto, $configuracion);
		}
		throw new BusinessLogicException("Error con el parametro de tipo foto");
	}

	public function borrarFoto(Foto $foto)
	{
		return $this->_fotoDao->borrar($foto);
	}
}
