<?php

use Dominio\Clases\Asignatura;

use Dominio\Excepciones\DBTransactionException;
use Dominio\Clases\Periodo;
use modules\HelperModules;
use Dominio\Excepciones\BusinessLogicException;
use modules\Periodo\VistaPeriodo;
use Dominio\Clases\Usuario;
use modules\Periodo\ModeloPeriodo;

require_once 'VistaPeriodo.php';
require_once 'ModeloPeriodo.php';
require_once '/../../Dominio/Clases/Usuario.php';
require_once '/../../Dominio/Excepciones/BusinessLogicException.php';
require_once '/../../Dominio/Excepciones/DBTransactionException.php';
require_once '/../HelperModules.php';

$modeloPeriodo = new ModeloPeriodo();
$vistaPeriodo = new VistaPeriodo();
//$usuarioApp = $_SESSION["usuario"];

//Si se pulsó algun boton
if(isset($_POST["action"])){

	$action = $_POST["action"];
	session_start();
	$usuarioApp = $_SESSION["usuario"];
	$idPeriodoRequerido = $_SESSION["idPeriodo"];
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
					$periodoEditado = new Periodo($idPeriodoRequerido, $_POST['fechaInicio'], $_POST['fechaFinal'], $_POST['descripcion'], $_POST['nombre']);
					$periodoEditado->setUsuario($usuarioApp);
					$modeloPeriodo->editarPeriodo($periodoEditado);

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

		}
	}

}else{

	//Si no es un evento, entonces se imprime la informacion del modulo

	$idPeriodoRequerido = $_SESSION["idPeriodo"];
	$periodoRequerido = $modeloPeriodo->obtenerPeriodoPorId($idPeriodoRequerido);

	//Si el periodo existe
	if($periodoRequerido !=null){

		//Si el usuario actual es dueño del periodo
		if($modeloPeriodo->comprobarSiUnPeriodoPerteneceAUnUsuario($idPeriodoRequerido, $usuarioApp->getEmail())){

			$datos = array (1 => $periodoRequerido,
					2=> $modeloPeriodo->obtenerListaDeAsignaturasDeUnPeriodo($periodoRequerido));
			$vistaPeriodo->imprimirHTML_Periodo($datos);
		}else{
			$_SESSION["mensajes"] = HelperModules::crearMensajeError("No esta autorizado para vistar la seccion a la cual estaba intentado acceder");
			HelperModules::redireccionarAlInicio();
		}
	}else{
		$_SESSION["mensajes"] = HelperModules::crearMensajeError("La seccion a la cual queria acceder, no existe");
		HelperModules::redireccionarAlInicio();
	}
}