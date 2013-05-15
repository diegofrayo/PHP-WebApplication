<?php

/**
 * Esta clase es un objeto de negocio.
 * Va a manejar toda la logica de las siguientes entemailades:
 * Usuario. Va a servir para hacer el login, registro, y busqueda
 */

use Dominio\Excepciones\BusinessLogicException;
use Dominio\Daos\DaoGrupoDeNotas;
use Dominio\Daos\DaoUsuario;
use Dominio\Daos\DaoNota;
use Dominio\Daos\DaoAsignatura;
use Dominio\Clases\GrupoDeNotas;
use Dominio\Clases\Usuario;
use Dominio\Clases\Asignatura;
use Dominio\Clases\Nota;

class BoUsuarios
{
	private $_usuarioDao;

	public function __construct()
	{
		$this->_usuarioDao = new DaoUsuario();
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
			if($this->comprarDisponibilidadNick($usuario->getNick()) == false){
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
}
