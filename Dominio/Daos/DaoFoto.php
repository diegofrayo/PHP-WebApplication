<?php

namespace Dominio\Daos;

use Dominio\Excepciones\DBTransactionException;
use Dominio\Clases\Foto;
use Dominio\BaseDeDatos\BDFactory;
use Dominio\IDaos\IDaoFoto;
use Dominio\DTO\DTOCrud;

$_SERVER['DOCUMENT_ROOT'] = 'C:/xampp/htdocs/Qualify';

require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/IDaos/IDaoFoto.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Clases/Foto.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/BaseDeDatos/BDFactory.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/DTO/DTOCrud.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Excepciones/DBTransactionException.php';

class DaoFoto implements IDaoFoto
{
	public function crear(Foto $foto, $configuracion)
	{

		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "insert into foto (id, ubicacion)
				" . " values (?,?)";
		$arrayDatos = array($foto->getId(),$foto -> getUbicacion());
		$DTOConsulta = $manejadorBD -> insertar($consultaSQL, $arrayDatos);

		if ($DTOConsulta->getExitoConsulta() ==true){

			//Se crea el archivo
			if($configuracion == "defecto"){

				//Archivo default
				$fsrc = fopen($_SERVER['DOCUMENT_ROOT']."/media/img/avatar.png",'r');

				//Nuevo archivo (copia, y nuevo nombre)
				$fdest = fopen($_SERVER['DOCUMENT_ROOT']."/public/avatar/".$foto->getId().".png",'w+');

				stream_copy_to_stream($fsrc,$fdest);
				fclose($fsrc);
				fclose($fdest);
			}else{
				//Se crea la foto con los datos obtenidos
				$uploadfile = $_SERVER['DOCUMENT_ROOT']."/".$foto->getUbicacion();
				move_uploaded_file($_FILES['foto']['tmp_name'], $uploadfile);
			}

			return $foto;
		}

		throw new DBTransactionException();
	}

	public function borrar(Foto $foto)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "delete from foto where id = ? ";
		$exitoConsulta = $manejadorBD ->eliminar($consultaSQL, array($foto->getId()));

		if ($exitoConsulta == true){
			unlink($_SERVER['DOCUMENT_ROOT']."/".$foto->getUbicacion());
			return $foto;
		}

		throw new DBTransactionException();

	}

	public function editar(Foto $foto)
	{

	}

	public function obtenerFotoPorId($id)
	{
		$manejadorBD = BDFactory::crearManejadorBD();
		$consultaSQL = "select * from foto where id = ?";
		$resultados = $manejadorBD -> obtenerDatos($consultaSQL, array($id));

		if(count($resultados)==1){
			$nuevaFoto = $resultados[0];
			return new Foto($nuevaFoto['id'],$nuevaFoto['ubicacion']);
		}

		return null;
	}

}
