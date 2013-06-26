<?php

use Dominio\DTO\DTOModuloHeader;

use modules\HelperModules;

use modules\HelperModule;

use Dominio\Excepciones\BusinessLogicException;
use modules\Header\VistaHeader;
use Dominio\Clases\Usuario;
use modules\Header\ModeloHeader;

require_once $_SERVER['DOCUMENT_ROOT'].'/modules/Header/VistaHeader.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/Header/ModeloHeader.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/HelperModules.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Excepciones/BusinessLogicException.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Excepciones/DBTransactionException.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/DTO/DTOModuloHeader.php';

$modeloHeader = new ModeloHeader();
$vistaHeader = new VistaHeader();
$usuarioApp = $_SESSION["usuario"];

//Si se pulsó algun boton
if(isset($_POST["action"])){

	$action = $_POST["action"];

	$usuarioApp = $_SESSION["usuario"];

	switch ($action){
		case 'Cerrar Sesion':
			$_SESSION["usuario"] =  "Visitante";
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
		try{
			$dtoHeader = new DTOModuloHeader();
			$dtoHeader->setNickUsuario($usuarioApp['nick']);
			$dtoHeader->setFotoUsuario( $usuarioApp['foto']['ubicacion']);
			$dtoHeader->setListaNotificacionesUsuario($modeloHeader->obtenerNotificacionesDelUsuario(Usuario::arrayToUser($usuarioApp)));
			$vistaHeader->imprimirHTML_UsuarioLogueado($dtoHeader);
		}catch (BusinessLogicException $e1){
			$_SESSION["mensajes"] = $e1->__toString();
		}catch (DBTransactionException $e2){
			$_SESSION["mensajes"] = $e2->__toString();
		}
	}
}
