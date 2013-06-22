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

require_once $_SERVER['DOCUMENT_ROOT'].'/modules/Asignatura/VistaAsignatura.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/Asignatura/ModeloAsignatura.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Clases/Asignatura.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Excepciones/BusinessLogicException.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Excepciones/DBTransactionException.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/HelperModules.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/Periodo/ModeloPeriodo.php';

$modeloAsignatura = new ModeloAsignatura();
$vistaAsignatura = new VistaAsignatura();
$modeloPeriodo = new ModeloPeriodo();
//$usuarioApp = $_SESSION["usuario"];

//Si se pulsó algun boton
if(isset($_POST["action"])){

	$action = $_POST["action"];
	session_start();
	$usuarioApp = $_SESSION["usuario"];

	//Si el evento, es ajax
	if(isset($_POST["ajax"])){

		switch ($action){
			case 'Borrar Nota':
				try{
					if(isset($_POST['id'])){
						
						$notaABorrar = new Nota($_POST['id'], "", 0, 0, null);
						$notaABorrar = $modeloAsignatura->borrarNota($notaABorrar);
						if($notaABorrar!=null){
							echo HelperModules::crearMensajeExito("La nota se ha borrado");
						}else{
							echo HelperModules::crearMensajeError("No se pudo borrar la nota");
						}
					}

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
						$_SESSION["mensajes"] = HelperModules::crearMensajeExito("Se ha creado la nota");
					}else{
						$_SESSION["mensajes"] = HelperModules::crearMensajeError("El grupo de notas seleccionado no existe");
					}

				}catch (BusinessLogicException $e1){
					$_SESSION["mensajes"] = $e1->__toString();
				}catch (DBTransactionException $e2){
					$_SESSION["mensajes"] = $e2->__toString();
				}
				header("Location: " . $_SERVER['HTTP_REFERER']);
				break;

			case 'Editar Asignatura':

				try{
					$asignaturaEditada= new Asignatura($_POST['idAsignatura'], $_POST['nombre']);
					$periodoAsignatura = $modeloPeriodo->obtenerPeriodoPorId($_POST['periodo']);
					$asignaturaEditada->setPeriodo($periodoAsignatura);
					$modeloAsignatura->editarAsignatura($asignaturaEditada);
					$_SESSION["mensajes"] = HelperModules::crearMensajeExito("La asignatura se ha editado");
				}catch (BusinessLogicException $e1){
					$_SESSION["mensajes"] = $e1->__toString();
				}catch (DBTransactionException $e2){
					$_SESSION["mensajes"] = $e2->__toString();
				}
				header("Location: " . $_SERVER['HTTP_REFERER']);
				break;

			case 'Borrar Asignatura':

				try{
					$asignaturaBorrar = new Asignatura($_POST['idAsignatura'], "Asignatura 2");
					$modeloAsignatura->borrarAsignatura($asignaturaBorrar);
					$_SESSION["mensajes"] = HelperModules::crearMensajeExito("La asignatura se ha borrado");
				}catch (BusinessLogicException $e1){
					$_SESSION["mensajes"] = $e1->__toString();
				}catch (DBTransactionException $e2){
					$_SESSION["mensajes"] = $e2->__toString();
				}
				header("Location: " . $_SERVER['HTTP_REFERER']);
				break;

		}
	}

}