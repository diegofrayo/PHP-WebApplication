<?php

use Dominio\Excepciones\DBTransactionException;
use Dominio\Clases\Periodo;
use modules\HelperModules;
use Dominio\Excepciones\BusinessLogicException;
use modules\Home\VistaHome;
use Dominio\Clases\Usuario;
use modules\Home\ModeloHome;

require_once 'VistaHome.php';
require_once 'ModeloHome.php';
require_once '/../../Dominio/Clases/Usuario.php';
require_once '/../../Dominio/Excepciones/BusinessLogicException.php';
require_once '/../../Dominio/Excepciones/DBTransactionException.php';
require_once '/../HelperModules.php';

$modeloHome = new ModeloHome();
$vistaHome = new VistaHome();
//$usuarioApp = $_SESSION["usuario"];

//Si se pulsó algun boton
if(isset($_POST["action"])){

	$action = $_POST["action"];
	session_start();
	$usuarioApp = $_SESSION["usuario"];

	switch ($action){

		case 'Registrar':
			try{
				$usuarioRegistrado = $modeloHome->registrarUsuario(new Usuario($_POST["email"],$_POST["nombre"], $_POST["nick"], $_POST["password"],"foto"));
				$_SESSION["usuario"] = null;
				$_SESSION["usuario"] = $usuarioRegistrado;
				$_SESSION["mensajes"] = HelperModules::crearMensajeExito("Se ha registrador con éxito");
			}catch (BusinessLogicException $e1){

				$_SESSION["mensajes"] = $e1->__toString();

			}catch (DBTransactionException $e2){

				$_SESSION["mensajes"] = $e2->__toString();

			}
			HelperModules::redireccionarAlInicio();
			break;

		case 'Login':
			if($_POST["email"] != '' && $_POST["password"]!=''){
				try{
					$usuarioLogueado = $modeloHome->iniciarSesionUsuario($_POST["email"], $_POST["password"]);
					$_SESSION["usuario"] = null;
					$_SESSION["usuario"] = $usuarioLogueado;
				}catch (BusinessLogicException $e1){

					$_SESSION["mensajes"] = $e1->__toString();

				}catch (DBTransactionException $e2){

					$_SESSION["mensajes"] = $e2->__toString();

				}
			}else{
				$_SESSION["mensajes"] = HelperModules::crearMensajeError("Tiene que rellenar los campos");
			}
			HelperModules::redireccionarAlInicio();
			break;

		case 'Crear Periodo':

			try{
				$periodoNuevo = new Periodo(0, $_POST['fechaInicio'], $_POST['fechaFinal'], $_POST['descripcion'], $_POST['nombre']);
				$periodoNuevo->setUsuario($usuarioApp);
				$usuarioLogueado = $modeloHome->crearPeriodo($periodoNuevo);
				$_SESSION["mensajes"] = HelperModules::crearMensajeExito("El periodo ha sido creado con éxito");
			}catch (BusinessLogicException $e1){
					
				$_SESSION["mensajes"] = $e1->__toString();
					
			}catch (DBTransactionException $e2){
					
				$_SESSION["mensajes"] = $e2->__toString();
					
			}
			HelperModules::redireccionarAlInicio();
			break;
	}

}else{
	//Si no es un evento, entonces se imprime la informacion del modulo

	//Imprime el contenido, que depende del usuario
	if($usuarioApp == "Visitante"){
		$vistaHome->imprimirHTML_UsuarioNoLogueado();
	}else{
		$datos = array (1 => $modeloHome->obtenerListaDePeriodosDeUnUsuario($usuarioApp),
				2=> "Feed de noticias");
		$vistaHome->imprimirHTML_UsuarioLogueado($datos);
	}
}