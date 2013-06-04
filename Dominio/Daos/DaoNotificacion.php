<?php

namespace Dominio\Daos;

use Dominio\Clases\Usuario;

use Dominio\Excepciones\DBTransactionException;
use Dominio\Clases\Notificacion;
use Dominio\BaseDeDatos\BDFactory;
use Dominio\IDaos\IDaoNotificacion;
use Dominio\DTO\DTOCrud;

require_once '/../IDaos/IDaoNotificacion.php';
require_once '/../Clases/Notificacion.php';
require_once '/../BaseDeDatos/BDFactory.php';
require_once '/../DTO/DTOCrud.php';
require_once '/../Excepciones/DBTransactionException.php';

class DaoNotificacion implements IDaoNotificacion
{
	public function crear(Notificacion $notificacion)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "insert into notificacion (id, link, texto, usuario, es_nueva, fecha)
				" . " values (?,?,?,?,?, ?)";
		$arrayDatos = array($notificacion -> getId(),$notificacion -> getLink(),
				$notificacion -> getTexto(), $notificacion->getUsuario()->getEmail(),
				$notificacion -> getEsNueva(), $notificacion->getFecha());
		$DTOConsulta = $manejadorBD -> insertar($consultaSQL, $arrayDatos);

		if ($DTOConsulta->getExitoConsulta() ==true){
			$notificacion->setId($DTOConsulta->getUltimoId());
			return $notificacion;
		}

		throw new DBTransactionException();
	}

	public function editar(Notificacion $notificacion)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "update notificacion set es_nueva = ? where id = ? ";
		$arrayDatos = array($notificacion -> getEsNueva(), $notificacion -> getId());
		$DTOConsulta = $manejadorBD -> insertar($consultaSQL, $arrayDatos);

		if ($DTOConsulta->getExitoConsulta() ==true){
			$notificacion->setId($DTOConsulta->getUltimoId());
			return $notificacion;
		}

		throw new DBTransactionException();
	}


	public function obtenerListaDeNotificacionesDeUnUsuario(Usuario $usuario)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "select * from notificacion where usuario = ? ORDER BY fecha ASC";
		$resultados = $manejadorBD -> obtenerDatos($consultaSQL, array($usuario->getEmail()));
		$numeroResultados = count($resultados);
		$listaNotificaciones= array();

		if($numeroResultados!=0){
			for ($i = 0; $i<$numeroResultados; $i++){
				$nuevaNotificacion = $resultados[$i];
				$notificacionLeida = new Notificacion($nuevaNotificacion['id'],$nuevaNotificacion['link'],
						$nuevaNotificacion['texto'], $nuevaNotificacion['fecha']);
				$notificacionLeida->setUsuario($usuario);
				$listaNotificaciones[] = $notificacionLeida;
			}
			return $listaNotificaciones;
		}
		return null;
	}
}