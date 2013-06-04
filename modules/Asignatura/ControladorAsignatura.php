<?php

use modules\Periodo\ModeloPeriodo;

use Dominio\Clases\Nota;
use Dominio\Clases\Asignatura;
use Dominio\Excepciones\DBTransactionException;
use modules\HelperModules;
use Dominio\Excepciones\BusinessLogicException;
use modules\Asignatura\VistaAsignatura;
use Dominio\Clases\Usuario;
use modules\Asignatura\ModeloAsignatura;

require_once 'VistaAsignatura.php';
require_once 'ModeloAsignatura.php';
require_once '/../../Dominio/Clases/Usuario.php';
require_once '/../../Dominio/Excepciones/BusinessLogicException.php';
require_once '/../../Dominio/Excepciones/DBTransactionException.php';
require_once '/../HelperModules.php';
require_once '/../Periodo/ModeloPeriodo.php';

$modeloAsignatura = new ModeloAsignatura();
$vistaAsignatura = new VistaAsignatura();
$modeloPeriodo = new ModeloPeriodo();
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
					$promedio = $modeloAsignatura->calcularPromedioFinalDeUnAsignatura($asignaturaRequerida);
					echo "El promedio del asignatura es de: ".$promedio;
				}catch (BusinessLogicException $e1){

					$_SESSION["mensajes"] = $e1->__toString();

				}catch (DBTransactionException $e2){

					$_SESSION["mensajes"] = $e2->__toString();

				}

				break;
		}

	}else{

		switch ($action){

			case 'Crear Nota':

				try{
					if(isset($_POST['grupo'])){
						$grupo = $modeloAsignatura->obtenerGrupoDeNotasPorId($_POST['grupo']);
						$nuevaNota = new Nota(0, $_POST['nombre'], $_POST['valor'], $_POST['porcentaje'],$_POST['fecha']);
						$nuevaNota->setGrupo($grupo);
						$modeloAsignatura->crearNota($nuevaNota);
						$_SESSION["mensajes"] = HelperModules::crearMensajeExito("Se ha creado la nota con éxito");
					}else{
						$_SESSION["mensajes"] = HelperModules::crearMensajeError("El grupo de notas seleccionado no existe");
					}

				}catch (BusinessLogicException $e1){

					$_SESSION["mensajes"] = $e1->__toString();

				}catch (DBTransactionException $e2){

					$_SESSION["mensajes"] = $e2->__toString();

				}
				HelperModules::redireccionar("periodo/".$idPeriodoRequerido);
				break;

			case 'Editar Asignatura':

				try{
					$asignaturaEditada= new Asignatura( $_POST['idAsignatura'], $_POST['nombre'],  $_POST['notas']);
					$asignaturaEditada->setNotaFinal($notaFinal);
					$periodoAsignatura = $modeloPeriodo->obtenerPeriodoPorId($_POST['periodo']);
					$asignaturaEditada->setPeriodo($periodoAsignatura);
					$modeloAsignatura->editarAsignatura($asignaturaEditada);
					$_SESSION["mensajes"] = HelperModules::crearMensajeExito("La asignatura se ha editado con éxito");
				}catch (BusinessLogicException $e1){

					$_SESSION["mensajes"] = $e1->__toString();

				}catch (DBTransactionException $e2){

					$_SESSION["mensajes"] = $e2->__toString();

				}
				HelperModules::redireccionar("periodo/".$idPeriodoRequerido);
				break;

		}
	}

}