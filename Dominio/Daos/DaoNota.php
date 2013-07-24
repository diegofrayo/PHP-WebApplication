<?php

namespace Dominio\Daos;

use Dominio\Clases\GrupoDeNotas;
use Dominio\Excepciones\DBTransactionException;
use Dominio\Clases\Nota;
use Dominio\BaseDeDatos\BDFactory;
use Dominio\IDaos\IDaoNota;
use Dominio\DTO\DTOCrud;

//$_SERVER['DOCUMENT_ROOT'] = 'C:/xampp/htdocs/Qualify';

require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/IDaos/IDaoNota.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Clases/Nota.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/BaseDeDatos/BDFactory.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/DTO/DTOCrud.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Excepciones/DBTransactionException.php';

class DaoNota implements IDaoNota
{
	public function crear(Nota $nota)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "insert into nota (id, nombre, valor, porcentaje, grupo, fecha, nick_usuario)
				" . " values (?,?,?,?,?,?,?)";
		$arrayDatos = array($nota -> getId(),$nota -> getNombre(), $nota -> getValor(),
				$nota->getPorcentaje(), $nota -> getGrupo()->getId(), $nota->getFecha(), $nota->getUsuario()->getNick());
		$DTOConsulta = $manejadorBD -> insertar($consultaSQL, $arrayDatos);

		if ($DTOConsulta->getExitoConsulta() ==true){
			$nota->setId($DTOConsulta->getUltimoId());
			return $nota;
		}

		throw new DBTransactionException();
	}

	public function borrar(Nota $nota)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "delete from nota where id = ? ";
		$exitoConsulta = $manejadorBD ->eliminar($consultaSQL, array($nota->getId()));

		if ($exitoConsulta ==true){
			return $nota;
		}

		throw new DBTransactionException();
	}

	public function editar(Nota $nota)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "update nota set nombre=?, valor=?, porcentaje=?, fecha=? where id = ?" ;
		$arrayDatos = array($nota -> getNombre(), $nota -> getValor(), $nota->getPorcentaje(), $nota->getFecha(),$nota -> getId());
		$exitoConsulta = $manejadorBD ->editar($consultaSQL, $arrayDatos );

		if ($exitoConsulta ==true){
			return $nota;
		}

		throw new DBTransactionException();
	}

	public function obtenerListaDeNotasDeUnGrupo(GrupoDeNotas $grupo)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "select * from nota where grupo = ?";
		$resultados = $manejadorBD -> obtenerDatos($consultaSQL, array($grupo->getId()));
		$numeroResultados = count($resultados);
		$listaNotas = array();

		if($numeroResultados!=0){
			for ($i = 0; $i<$numeroResultados; $i++){
				$nuevaNota = $resultados[$i];
				$notaLeida = new Nota($nuevaNota['id'],$nuevaNota['nombre'],
						$nuevaNota['valor'],$nuevaNota['porcentaje'] ,$nuevaNota['fecha']);
				$notaLeida->setGrupo($grupo);
				$listaNotas[] = $notaLeida;
			}
		}

		return $listaNotas;
	}

	public function obtenerNotaPorId($id){

		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "select * from nota where id = ?";
		$resultados = $manejadorBD -> obtenerDatos($consultaSQL, array($id));

		if(count($resultados)==1){
			$daoGrupo = new DaoGrupoDeNotas();
			$nuevaNota = $resultados[0];
			$notaLeida = new Nota($nuevaNota['id'],$nuevaNota['nombre'],
					$nuevaNota['valor'], $nuevaNota['porcentaje'] ,$nuevaNota['fecha']);
			$notaLeida->setGrupo($daoGrupo->obtenerGrupoPorId($nuevaNota['grupo']));
			return $notaLeida;
		}

		return null;

	}

	public function obtenerNotasFuturas($fecha ,$nickUsuario)
	{

		// 		$consultaSQL = "select nota.id,nota.nombre,nota.valor,nota.porcentaje,nota.fecha,nota.grupo ".
		// 				"from nota,grupo_de_notas,asignatura,periodo,usuario where periodo.usuario = ? and ".
		// 				"periodo.id = asignatura.periodo and asignatura.id = grupo_de_notas.asignatura ".
		// 				"grupo_de_notas.id = nota.grupo";
		// 		$consultaSQL = "select * from nota inner join grupo_de_notas on nota.grupo_de_notas = grupo_de_notas.id ".
		// 				"inner join asignatura on grupo_de_notas.asignatura = asignatura.id inner join periodo on asignatura.periodo = periodo.id"
		// 				."inner join usuario on periodo.usuario = ? where nota.fecha  >= ?";
			
		// 		$consultaSQL = "select nota.id,nota.nombre,nota.valor,nota.porcentaje,nota.fecha,nota.grupo_de_notas ".
		// 				"from nota,grupo_de_notas,asignatura,periodo,usuario where nota.grupo_de_notas = grupo_de_notas.id and ".
		// 				"grupo_de_notas.asignatura = asignatura.id and asignatura.periodo = periodo.id and ".
		// 				"periodo.usuario = ? and nota.fecha  >= ?";
		// 		$consultaSQL = "select n.id,n.nombre,n.valor,n.porcentaje,n.fecha,n.grupo_de_notas ".
		// 				"from nota AS n INNER JOIN grupo_de_notas AS g ON n.grupo_de_notas = g.id ".
		// 				"INNER JOIN asignatura AS a ON g.asignatura = a.id ".
		// 				"INNER JOIN periodo AS p ON a.periodo = p.id ".
		// 				"INNER JOIN usuario AS u ON p.usuario = u.email ".
		// 				"where u.email = ?";
		//"where p.usuario = ? and n.fecha >= ?";
		//$resultados = $manejadorBD -> obtenerDatos($consultaSQL, array($emailUsuario,$fecha));

		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "select * from nota where nick_usuario = ? and fecha >= ? ";
		$resultados = $manejadorBD -> obtenerDatos($consultaSQL, array($nickUsuario, $fecha));
		$numeroResultados = count($resultados);
		$listaNotas = array();

		if($numeroResultados!=0){
			$daoGrupo = new DaoGrupoDeNotas();
			for ($i = 0; $i<$numeroResultados; $i++){
				$nuevaNota = $resultados[$i];
				$notaLeida = new Nota($nuevaNota['id'],$nuevaNota['nombre'],
						$nuevaNota['valor'],$nuevaNota['porcentaje'] ,$nuevaNota['fecha']);
				$notaLeida->setGrupo($daoGrupo->obtenerGrupoPorId($nuevaNota['grupo']));
				$listaNotas[] = $notaLeida;
			}
		}

		return $listaNotas;
	}

}