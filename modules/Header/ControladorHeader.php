<?php

use modules\HelperModules;

use modules\HelperModule;

use Dominio\Excepciones\BusinessLogicException;
use modules\Header\VistaHeader;
use Dominio\Clases\Usuario;
use modules\Header\ModeloHeader;

require_once 'VistaHeader.php';
require_once 'ModeloHeader.php';
require_once '/../HelperModules.php';
require_once '/../../Dominio/Excepciones/BusinessLogicException.php';
require_once '/../../Dominio/Excepciones/DBTransactionException.php';

$modeloHeader = new ModeloHeader();
$vistaHeader = new VistaHeader();
$usuarioApp = $_SESSION["usuario"];

//Si se pulsó algun boton
if(isset($_POST["action"])){

	$action = $_POST["action"];
	session_start();
	$usuarioApp = $_SESSION["usuario"];

	switch ($action){
		case 'Cerrar Sesion':
			$_SESSION["usuario"] =  null;
			$_SESSION["mensajes"] = "";
			HelperModules::redireccionarAlInicio();
			break;

		case 'Notificaciones':

			break;
	}


}else{
	//Si no es un evento, entonces se imprime la informacion del modulo

	//Imprime el contenido, que depende del usuario
	if($usuarioApp == "Visitante"){
		$vistaHeader->imprimirHTML_UsuarioNoLogueado();
	}else{
		$datos = array (1=> $usuarioApp->getNick(), 2 => $modeloHeader->obtenerNotificacionesDelUsuario($usuarioApp)
		);
		$vistaHeader->imprimirHTML_UsuarioLogueado($datos);
	}
}
