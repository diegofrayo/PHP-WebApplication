<?php

use domain\DTO\DTOModuloHeader;

use modules\HelperModules;

use modules\HelperModule;

use domain\exceptions\BusinessLogicException;
use modules\header\VistaHeader;
use domain\classes\Usuario;
use modules\header\ModeloHeader;

$_SERVER['DOCUMENT_ROOT'] = 'C:/xampp/htdocs/Qualify';

require_once $_SERVER['DOCUMENT_ROOT'].'/modules/header/VistaHeader.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/HelperModules.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/domain/exceptions/BusinessLogicException.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/domain/exceptions/DBTransactionException.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/domain/dto/DTOModuloHeader.php';

$vistaHeader = new VistaHeader();
$usuarioApp = $_SESSION["usuario"];

//Si se pulsó algun boton
if(isset($_POST["action"])){

	$action = $_POST["action"];
	session_start();

	switch ($action){
		
		case 'Cerrar Sesion':
			
			session_unset();
			session_destroy();
			session_start();
			session_regenerate_id(true);
			HelperModules::redireccionarAlInicio();
			break;
			
	}


}else{
	//Si no es un evento, entonces se imprime la informacion del modulo

	//Imprime el contenido, que depende del usuario
	if($usuarioApp == "Visitante"){
		
		$vistaHeader->imprimirHTML_UsuarioNoLogueado();
	}else{
		
		try{
			$dtoHeader = new DTOModuloHeader();
			$dtoHeader->setNickUsuario($usuarioApp['nick']);
			$vistaHeader->imprimirHTML_UsuarioLogueado($dtoHeader);
			
		}catch (BusinessLogicException $e1){
			
			$_SESSION["mensajes"] = $e1->__toString();
		}catch (DBTransactionException $e2){
			
			$_SESSION["mensajes"] = $e2->__toString();
		}
	}
}
