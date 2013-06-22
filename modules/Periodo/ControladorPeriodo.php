<?php

use modules\Home\ModeloHome;

use modules\Asignatura\ModeloAsignatura;

use Dominio\DTO\DTOModuloAsignatura;

use Dominio\DTO\DTOModuloPeriodo;

use Dominio\Clases\Asignatura;

use Dominio\Excepciones\DBTransactionException;
use Dominio\Clases\Periodo;
use modules\HelperModules;
use Dominio\Excepciones\BusinessLogicException;
use modules\Periodo\VistaPeriodo;
use Dominio\Clases\Usuario;
use modules\Periodo\ModeloPeriodo;

require_once $_SERVER['DOCUMENT_ROOT'].'/modules/Periodo/VistaPeriodo.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/Periodo/ModeloPeriodo.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Clases/Usuario.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Excepciones/BusinessLogicException.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Excepciones/DBTransactionException.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/HelperModules.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/DTO/DTOModuloPeriodo.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/DTO/DTOModuloAsignatura.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/Asignatura/ModeloAsignatura.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/Home/ModeloHome.php';

$modeloPeriodo = new ModeloPeriodo();
$vistaPeriodo = new VistaPeriodo();
$modeloAsignatura = new ModeloAsignatura();
$modeloHome = new ModeloHome();
//$usuarioApp = $_SESSION["usuario"];

//Si se pulsó algun boton
if(isset($_POST["action"])){

	$action = $_POST["action"];
	session_start();
	$usuarioApp = $_SESSION["usuario"];
	$idPeriodoRequerido = $_POST["id"];
	$periodoRequerido = $modeloPeriodo->obtenerPeriodoPorId($idPeriodoRequerido);

	//Si el evento, es ajax
	if(isset($_POST["ajax"])){

		switch ($action){

			case 'Calcular Promedio':

				try{
					$promedio = $modeloPeriodo->calcularPromedioFinalDeUnPeriodo($periodoRequerido);
					echo "El promedio del periodo es de: ".$promedio;
				}catch (BusinessLogicException $e1){
					$_SESSION["mensajes"] = $e1->__toString();
				}catch (DBTransactionException $e2){
					$_SESSION["mensajes"] = $e2->__toString();
				}

				break;
		}

	}else{

		switch ($action){

			case 'Editar Periodo':

				try{
					$periodoEditado = new Periodo($idPeriodoRequerido, $_POST['fechaInicio'], $_POST['fechaFinal'], $_POST['nombre']);
					$periodoEditado->setUsuario(Usuario::arrayToUser($usuarioApp));
					$modeloPeriodo->editarPeriodo($periodoEditado);
					$_SESSION["mensajes"] = HelperModules::crearMensajeExito("Se ha editado el periodo");
				}catch (BusinessLogicException $e1){

					$_SESSION["mensajes"] = $e1->__toString();

				}catch (DBTransactionException $e2){

					$_SESSION["mensajes"] = $e2->__toString();

				}
				HelperModules::redireccionar("periodo/".$idPeriodoRequerido);
				break;

			case 'Crear Asignatura':

				try{
					$nuevaAsignatura= new Asignatura(0, $_POST['nombre'],$_POST['notas']);
					$nuevaAsignatura->setPeriodo($periodoRequerido);
					$modeloPeriodo->crearAsignatura($nuevaAsignatura);
				}catch (BusinessLogicException $e1){

					$_SESSION["mensajes"] = $e1->__toString();

				}catch (DBTransactionException $e2){

					$_SESSION["mensajes"] = $e2->__toString();

				}
				HelperModules::redireccionar("periodo/".$idPeriodoRequerido);
				break;

			case 'Borrar Periodo':

				try{
					$modeloPeriodo->borrarPeriodo($periodoRequerido);
					$_SESSION["mensajes"] = HelperModules::crearMensajeExito("Se ha borrado el periodo");
				}catch (BusinessLogicException $e1){

					$_SESSION["mensajes"] = $e1->__toString();

				}catch (DBTransactionException $e2){

					$_SESSION["mensajes"] = $e2->__toString();

				}
				HelperModules::redireccionarAlInicio();
				break;

		}
	}

}else{

	//Si no es un evento, entonces se imprime la informacion del modulo
	try{
		$idPeriodoRequerido = $_GET["id"];
		$periodoRequerido = $modeloPeriodo->obtenerPeriodoPorId($idPeriodoRequerido);

		//Si el periodo existe
		if($periodoRequerido !=null){

			//Si el usuario actual es dueño del periodo
			if($modeloPeriodo->comprobarSiUnPeriodoPerteneceAUnUsuario($idPeriodoRequerido, $usuarioApp['email'])){

				//Datos DTO Periodo
				$dtoPeriodo = new DTOModuloPeriodo();
				$listaAsignaturasDeUnPeriodo = $modeloPeriodo->obtenerListaDeAsignaturasDeUnPeriodo($periodoRequerido);
				$listaDePeriodosDeUnUsuario = $modeloHome->obtenerListaDePeriodosDeUnUsuario(Usuario::arrayToUser($usuarioApp));
				$dtoPeriodo->setListaAsignaturasDeUnPeriodo($listaAsignaturasDeUnPeriodo);
				$dtoPeriodo->setPeriodo($periodoRequerido);

				//Datos Lista DTO Asignaturas
				$listaDtoAsignaturas = array();
				$indiceAsignaturas = 1;
				foreach ($listaAsignaturasDeUnPeriodo as $asignatura){
					$listaDeGrupos = $modeloAsignatura->obtenerListaDeGruposDeUnaAsignatura($asignatura);
					$dtoAsignatura = new DTOModuloAsignatura();
					$dtoAsignatura->setAsignatura($asignatura);
					$dtoAsignatura->setListaDeGrupos($listaDeGrupos);
					$dtoAsignatura->setListaDePeriodosDeUnUsuario($listaDePeriodosDeUnUsuario);
					$dtoAsignatura->setIndice($indiceAsignaturas);

					$matrizListaDeNotasDeUnGrupo = array();
					foreach($listaDeGrupos as $grupo){
						$matrizListaDeNotasDeUnGrupo[] = $modeloAsignatura->obtenerListaDeNotasDeUnGrupo($grupo);
					}
					$dtoAsignatura->setMatrizListaDeNotasDeUnGrupo($matrizListaDeNotasDeUnGrupo);
					$listaDtoAsignaturas[] = $dtoAsignatura;
					$indiceAsignaturas += 1;
				}

				$vistaPeriodo->imprimirHTML_Periodo($dtoPeriodo, $listaDtoAsignaturas);
			}else{
				$_SESSION["mensajes"] = HelperModules::crearMensajeError("No esta autorizado para visitar la seccion a la cual estaba intentado acceder");
				HelperModules::redireccionarAlInicio();
			}
		}else{
			$_SESSION["mensajes"] = HelperModules::crearMensajeError("La seccion a la cual queria acceder, no existe");
			HelperModules::redireccionarAlInicio();
		}
	}catch (BusinessLogicException $e1){
		$_SESSION["mensajes"] = $e1->__toString();
	}catch (DBTransactionException $e2){
		$_SESSION["mensajes"] = $e2->__toString();
	}
}