<?php

namespace Dominio\ObjetosDeNegocio;

/**
 * Esta clase es un objeto de negocio.
 * Va a manejar toda la logica de las siguientes entidades:
 * Notificaciones, RelacionesEntreUsuario, Comentarios, noticias
 */

use Dominio\Daos\DaoNoticia;
use Dominio\Excepciones\BusinessLogicException;
use Dominio\Daos\DaoRelacionEntreUsuarios;
use Dominio\Daos\DaoComentario;
use Dominio\Daos\DaoNotificacion;
use Dominio\Clases\RelacionEntreUsuarios;
use Dominio\Clases\Notificacion;
use Dominio\Clases\Comentario;

require_once '/../Daos/DaoRelacionEntreUsuarios.php';
require_once '/../Daos/DaoComentario.php';
require_once '/../Daos/DaoNotificacion.php';
require_once '/../Daos/DaoNoticia.php';
require_once '/../Clases/Notificacion.php';
require_once '/../Clases/Comentario.php';
require_once '/../Clases/RelacionEntreUsuarios.php';
require_once '/../Clases/Noticia.php';
require_once '/../Excepciones/BusinessLogicException.php';

class BoSocial
{

	private $_comentariosDao;
	private $_notificacionDao;
	private $_relacionEntreUsuariosDao;
	private $_noticiasDao;

	public function __construct()
	{
		$this->_notificacionDao = new DaoNotificacion();
		$this->_comentariosDao = new DaoComentario();
		$this->_relacionEntreUsuariosDao = new DaoRelacionEntreUsuarios();
		$this->_noticiasDao = new DaoNoticia();
	}


	/**
	 * Metodo para crear una notificacion
	 * @param Notificacion $notificacion
	 * @return Notificacion
	 */
	public function crearNotificacion(Notificacion $notificacion)
	{
		return $this->_notificacionDao->crear($notificacion);
	}

	/**
	 * Metodo para editar una notificacion
	 * @param Notificacion $notificacion
	 * @return Notificacion
	 */
	public function editarNotificacion(Notificacion $notificacion)
	{
		return $this->_notificacionDao->editar($notificacion);
	}

	/**
	 * Metodo para obtener la lista de notificaciones de un usuario
	 * @param Usuario $usuario
	 * @return Ambigous <NULL, multitype:\Dominio\Clases\Notificacion >
	 */
	public function obtenerListaDeNotificacionesDeUnUsuario(Usuario $usuario)
	{
		return $this->_notificacionDao->obtenerListaDeNotificacionesDeUnUsuario($usuario);
	}

	/**
	 * Metodo para comentar una noticia
	 * @param Comentario $comentario
	 * @return Comentario
	 */
	public function comentarUnaNoticia(Comentario $comentario)
	{
		$comentario = $this->_comentariosDao->crear($comentario);
		$texto = "El usuario "+ $comentario->getUsuarioComentarista()->getNick() + " ha comentado una noticia tuya";
		$notificacionNueva = new Notificacion(0, "ntc/".$comentario->getNoticia()->getId(), $texto, true, $fecha);
		$notificacionNueva->setUsuario($comentario->getNoticia()->getUsuario->getEmail());
		$this->crearNotificacion($notificacionNueva);

		return true;
	}

	/**
	 * Metodo para borrar un comentario
	 * @param Comentario $comentario
	 * @return Comentario
	 */
	public function borrarComentario(Comentario $comentario)
	{
		return $this->_comentariosDao->borrar($comentario);
	}

	/**
	 * Metodo para obtener la lista de comentarios de una noticia
	 * @param Noticia $noticia
	 * @return Ambigous <NULL, multitype:\Dominio\Clases\Comentario >
	 */
	public function obtenerListaDeComentariosDeUnaNoticia(Noticia $noticia){
		return $this->_comentariosDao->obtenerListaDeComentariosDeUnaNoticia($noticia);
	}

	/**
	 * Metodo para obtener el numero de comentarios de una noticia
	 * @param Noticia $noticia
	 * @return number
	 */
	public function obtenerNumeroDeComentariosDeUnaNoticia(Noticia $noticia){
		return $this->_comentariosDao->obtenerNumeroDeComentariosDeUnaNoticia($noticia);
	}

	/**
	 * Metodo para crear un relacionEntreUsuarios. Es cuando un usuario agrega a otro
	 * @param RelacionEntreUsuarios $relacionEntreUsuarios
	 * @return RelacionEntreUsuarios
	 */
	public function agregarAmigo(RelacionEntreUsuarios $relacionEntreUsuarios)
	{
		return $this->_relacionEntreUsuariosDao->crear($relacionEntreUsuarios);
	}

	/**
	 * Metodo para borrar un relacionEntreUsuarios. Es cuando un usuario elimina al otro
	 * @param RelacionEntreUsuarios $relacionEntreUsuarios
	 * @return RelacionEntreUsuarios
	 */
	public function borrarAmigo(RelacionEntreUsuarios $relacionEntreUsuarios)
	{
		return $this->_relacionEntreUsuariosDao->borrar($relacionEntreUsuarios);
	}


	/**
	 * Metodo para editar un relacionEntreUsuarios. Es cuando un usuario acepta una solicitud de amistad
	 * @param RelacionEntreUsuarios $relacionEntreUsuarios
	 * @return RelacionEntreUsuarios
	 */
	public function aceptarSolicitudDeAmistad(RelacionEntreUsuarios $relacionEntreUsuarios)
	{
		$relacionEntreUsuarios = $this->_relacionEntreUsuariosDao->editar($relacionEntreUsuarios);
		$nickNuevoAmigo = $relacionEntreUsuarios->getUsuarioReceptor()->getNick();
		$texto = "El usuario "+ $nickNuevoAmigo + " te ha aceptado como amigo";
		$notificacionNueva = new Notificacion(0, "profile/".$nickNuevoAmigo, $texto, true, $fecha);
		$notificacionNueva->setUsuario($relacionEntreUsuarios->getUsuarioEmisor()->getEmail());
		$this->crearNotificacion($notificacionNueva);

		return true;
	}

	/**
	 * Metodo para obtener las solicitudes de amistad de un usuario
	 * @param Usuario $usuario
	 * @return Ambigous <NULL, multitype:\Dominio\Clases\RelacionEntreUsuarios >
	 */
	public function obtenerSolicitudesDeAmistad(Usuario $usuario)
	{
		return $this->_relacionEntreUsuariosDao->obtenerSolicitudesDeAmistad($usuario);
	}

	/**
	 * Metodo para obtener los amigos de un usuario
	 * @param Usuario $usuario
	 * @return multitype:
	 */
	public function obtenerAmigosDeUnUsuario(Usuario $usuario)
	{
		return $this->_relacionEntreUsuariosDao->obtenerAmigosDeUnUsuario($usuario);
	}

	/**
	 * Metodo para saber si 2 usuarios son amgigos
	 * @param RelacionEntreUsuarios $relacion
	 * @return boolean
	 */
	public function comprobarRelacionEntreUsuarios(RelacionEntreUsuarios $relacion)
	{
		return $this->_relacionEntreUsuariosDao->comprobarAmistad($relacion);
	}

	/**
	 * Metodo para crear una noticia
	 * @param Noticia $noticia
	 * @return Noticia
	 */
	public function crearNoticia(Noticia $noticia)
	{
		return $this->_noticiasDao->crear($noticia);
	}

	/**
	 * Metodo para borrar una noticia
	 * @param Noticia $noticia
	 * @return Noticia
	 */
	public function borrarNoticia(Noticia $noticia)
	{
		return $this->_noticiasDao->borrar($noticia);
	}

	/**
	 * Metodo para obtener las noticias propias de un usuario
	 * @param Usuario $usuario
	 * @return Ambigous <NULL, multitype:unknown >
	 */
	public function obtenerListaDeNoticiasDeUnUsuario(Usuario $usuario)
	{
		return $this->_noticiasDao->obtenerListaDeNoticiasDeUnUsuario($usuario);
	}

}
