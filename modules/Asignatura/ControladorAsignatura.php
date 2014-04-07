<?php

use domain\classes\GrupoDeNotas;

use modules\periodo\ModeloPeriodo;
use domain\classes\Nota;
use domain\classes\Asignatura;
use domain\exceptions\DBTransactionException;
use modules\HelperModules;
use domain\exceptions\BusinessLogicException;
use modules\asignatura\VistaAsignatura;
use domain\classes\Usuario;
use modules\asignatura\ModeloAsignatura;

$_SERVER['DOCUMENT_ROOT'] = 'C:/xampp/htdocs/Qualify';

require_once $_SERVER['DOCUMENT_ROOT'].'/modules/asignatura/VistaAsignatura.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/asignatura/ModeloAsignatura.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/domain/classes/Asignatura.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/domain/exceptions/BusinessLogicException.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/domain/exceptions/DBTransactionException.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/HelperModules.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/periodo/ModeloPeriodo.php';

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
						$_SESSION["script"] = HelperModules::crearScriptAsignatura($_POST['indiceAsignatura']);
						
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

			case 'Editar Nota':
				
				try{
					
					$grupo = $modeloAsignatura->obtenerGrupoDeNotasPorId($_POST['grupoEditar']);
					$notaEditada = new Nota($_POST['idNota'], $_POST['nombre'], $_POST['valor'], $_POST['porcentaje'],$_POST['fecha']);
					$notaEditada->setGrupo($grupo);
					$modeloAsignatura->editarNota($notaEditada);
					
					$_SESSION["mensajes"] = HelperModules::crearMensajeExito("Se ha editado la nota");
					
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
					$_SESSION["script"] = HelperModules::crearScriptAsignatura($_POST['indiceAsignatura']);
				
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

			case 'Borrar Grupo':
				
				try{
					
					$grupoABorrar = new GrupoDeNotas($_POST['idGrupo'], "", true, true);
					$modeloAsignatura->borrarGrupoDeNotas($grupoABorrar);
					
					$_SESSION["mensajes"] = HelperModules::crearMensajeExito("El grupo se ha borrado");
					$_SESSION["script"] = HelperModules::crearScriptAsignatura($_POST['indiceAsignatura']);
					
				}catch (BusinessLogicException $e1){
					
					$_SESSION["mensajes"] = $e1->__toString();
				}catch (DBTransactionException $e2){
					
					$_SESSION["mensajes"] = $e2->__toString();
				}
				
				header("Location: " . $_SERVER['HTTP_REFERER']);
				break;

			case 'Crear Grupo':
				
				try{
					
					$porcentajesIguales =	$_POST['porcentajesIguales'];

					if($porcentajesIguales == 'on'){
						
						$porcentajesIguales = true;
					}else{
						
						$porcentajesIguales = false;
					}

					$grupoNuevo= new GrupoDeNotas(0, $_POST['nombre'], $porcentajesIguales, false);
					$grupoNuevo->setAsignatura(new Asignatura($_POST['idAsignatura'],""));
					$modeloAsignatura->crearGrupoDeNotas($grupoNuevo);
					
					$_SESSION["mensajes"] = HelperModules::crearMensajeExito("El grupo se ha creado");
					$_SESSION["script"] = HelperModules::crearScriptAsignatura($_POST['indiceAsignatura']);
					
				}catch (BusinessLogicException $e1){
					
					$_SESSION["mensajes"] = $e1->__toString();
				}catch (DBTransactionException $e2){
					
					$_SESSION["mensajes"] = $e2->__toString();
				}
				
				header("Location: " . $_SERVER['HTTP_REFERER']);
				break;

			case 'Editar Grupo':
				
				try{
					
					$porcentajesIguales =	$_POST['porcentajesIguales'];

					if($porcentajesIguales == 'on'){
						
						$porcentajesIguales = true;
					}else{
						
						$porcentajesIguales = false;
					}

					if(	isset($_POST['nombre']) == false){
						
						$_POST['nombre'] = 'Calificaciones';
					}

					$grupoAEditar= new GrupoDeNotas($_POST['idGrupo'], $_POST['nombre'], $porcentajesIguales, $_POST['grupo_defecto']);
					$modeloAsignatura->editarGrupoDeNotas($grupoAEditar);
					
					$_SESSION["mensajes"] = HelperModules::crearMensajeExito("El grupo se ha editado");
					$_SESSION["script"] = HelperModules::crearScriptAsignatura($_POST['indiceAsignatura']);
					
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