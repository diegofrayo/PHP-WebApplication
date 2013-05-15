<?php

namespace Dominio\Daos;

use Dominio\Clases\Asignatura;

use Dominio\BaseDeDatos\BDFactory;
use Dominio\Clases\GrupoDeNotas;
use Dominio\IDaos\IDaoGrupoDeNotas;

require_once '/../IDaos/IDaoGrupoDeNotas.php';
require_once '/../Clases/GrupoDeNotas.php';
require_once '/../BaseDeDatos/BDFactory.php';

class DaoGrupoDeNotas implements IDaoGrupoDeNotas
{
	public function crear(GrupoDeNotas $grupoDeNotas)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "insert into grupo_de_notas (id, nombre,porcentajes_iguales,asignatura,es_grupo_defecto)
				" . " values (?,?,?,?,?)";
		$arrayDatos = array($grupoDeNotas -> getId(),$grupoDeNotas -> getNombre(), $grupoDeNotas -> getPorcentajesIguales(), $grupoDeNotas->getAsignatura()->getId(), $grupoDeNotas -> getEsGrupoDefecto());
		$DTOConsulta = $manejadorBD -> insertar($consultaSQL, $arrayDatos);

		if ($DTOConsulta->getExitoConsulta() ==true){
			$grupoDeNotas->setId($DTOConsulta->getUltimoId());
			return $grupoDeNotas;
		}

		throw new DBTransactionException();
	}

	public function borrar(GrupoDeNotas $grupoDeNotas)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "delete from grupo_de_notas where id = ? ";
		$exitoConsulta = $manejadorBD ->eliminar($consultaSQL, array($grupoDeNotas->getId()));

		if ($exitoConsulta ==true){
			return $grupoDeNotas;
		}

		throw new DBTransactionException();
	}

	public function editar(GrupoDeNotas $grupoDeNotas)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "update grupo_de_notas set nombre = ?,porcentajes_iguales = ?,asignatura = ? where id = ?" ;
		$arrayDatos = array($grupoDeNotas -> getNombre(), $grupoDeNotas -> getPorcentajesIguales(), $grupoDeNotas -> getAsignatura()->getId(), $grupoDeNotas -> getId());
		$exitoConsulta = $manejadorBD ->editar($consultaSQL, $arrayDatos );

		if ($exitoConsulta ==true){
			return $grupoDeNotas;
		}

		throw new DBTransactionException();
	}



	public function obtenerGrupoDefectoDeUnaAsignatura(Asignatura $asignatura)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "select * from grupo_de_notas where es_grupo_defecto = ? and asignatura = ?";
		$resultados = $manejadorBD -> obtenerDatos($consultaSQL, array(true, $asignatura->getId()));

		if(count($resultados)==1){
			$nuevoGrupo = $resultados[0];
			$grupoLeido = new GrupoDeNotas($nuevoGrupo['id'],$nuevoGrupo['nombre'],
					$nuevoGrupo['porcentajes_iguales'],	$nuevoGrupo['es_grupo_defecto']);
			$grupoLeido->setAsignatura($asignatura);
			return $grupoLeido;
		}

		return null;
	}


	public function obtenerGrupoPorId($id){
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "select * from grupo_de_notas where id = ?";
		$resultados = $manejadorBD -> obtenerDatos($consultaSQL, array($id));

		if(count($resultados)==1){
			$nuevoGrupo = $resultados[0];
			return new GrupoDeNotas($nuevoGrupo['id'],$nuevoGrupo['nombre'],
					$nuevoGrupo['porcentajes_iguales'],	$nuevoGrupo['es_grupo_defecto']);
		}

		return null;
	}

}