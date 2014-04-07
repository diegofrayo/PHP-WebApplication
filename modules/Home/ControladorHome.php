<?php

use domain\dto\DTOModuloHome;
use domain\exceptions\DBTransactionException;
use domain\classes\Periodo;
use modules\HelperModules;
use domain\exceptions\BusinessLogicException;
use modules\home\VistaHome;
use domain\classes\Usuario;
use modules\home\ModeloHome;

$_SERVER ['DOCUMENT_ROOT'] = 'C:/xampp/htdocs/Qualify';

require_once $_SERVER ['DOCUMENT_ROOT'] . '/modules/home/VistaHome.php';
require_once $_SERVER ['DOCUMENT_ROOT'] . '/modules/home/ModeloHome.php';
require_once $_SERVER ['DOCUMENT_ROOT'] . '/domain/classes/Usuario.php';
require_once $_SERVER ['DOCUMENT_ROOT'] . '/domain/exceptions/BusinessLogicException.php';
require_once $_SERVER ['DOCUMENT_ROOT'] . '/domain/exceptions/DBTransactionException.php';
require_once $_SERVER ['DOCUMENT_ROOT'] . '/modules/HelperModules.php';
require_once $_SERVER ['DOCUMENT_ROOT'] . '/domain/dto/DTOModuloHome.php';

$modeloHome = new ModeloHome ();
$vistaHome = new VistaHome ();
// $usuarioApp = $_SESSION["usuario"];

// Si se pulsó algun boton
if (isset ( $_POST ["action"] )) {
	
	$action = $_POST ["action"];
	session_start ();
	$usuarioApp = $_SESSION ["usuario"];
	
	// Si el evento, es ajax
	if (isset ( $_POST ["ajax"] )) {
		
		switch ($action) {
			
			case 'Comprobar Nick' :
				
				try {
					
					$estaDisponible = $modeloHome->comprarDisponibilidadNick ( $_POST ['nick'] );
					
					if ($estaDisponible == true) {
						
						echo "Disponible";
					} else {
						
						echo "No Disponible";
					}
					
				} catch ( BusinessLogicException $e1 ) {
					
					$_SESSION ["mensajes"] = $e1->__toString ();
				} catch ( DBTransactionException $e2 ) {
					
					$_SESSION ["mensajes"] = $e2->__toString ();
				}
				
				break;
			
			case 'Comprobar Email' :
				
				try {
					
					$usuario = $modeloHome->comprarDisponibilidadEmail ( $_POST ['email'] );
					
					if ($usuario == null) {
						
						echo "Disponible";
					} else {
						
						echo "No Disponible";
					}
					
				} catch ( BusinessLogicException $e1 ) {
					
					$_SESSION ["mensajes"] = $e1->__toString ();
				} catch ( DBTransactionException $e2 ) {
					
					$_SESSION ["mensajes"] = $e2->__toString ();
				}
				
				break;
		}
		
	} else {
		
		switch ($action) {
			
			case 'Registrar':
				
				try {
					
					$usuarioRegistrado = new Usuario ( $_POST ["email"], $_POST ["nick-registro"], $_POST ["nombre"], $_POST ["password-registro"] );
					
					// Creo el usuario
					$usuarioRegistrado = $modeloHome->registrarUsuario ( $usuarioRegistrado );
					
					$_SESSION ["usuario"] = Usuario::userToArray ( $usuarioRegistrado );
					$_SESSION ["mensajes"] = HelperModules::crearMensajeExito ( "Se ha registrado" );
					
				} catch ( BusinessLogicException $e1 ) {
					
					$_SESSION ["mensajes"] = $e1->__toString ();
				} catch ( DBTransactionException $e2 ) {
					
					$_SESSION ["mensajes"] = $e2->__toString ();
				}
				
				HelperModules::redireccionarAlInicio ();
				break;
			
			case 'Login' :
				
				if ($_POST ["nick"] != '' && $_POST ["password"] != '') {
					
					try {
						
						$usuarioLogueado = $modeloHome->iniciarSesionUsuario ( $_POST ["nick"], $_POST ["password"] );
						$_SESSION ["usuario"] = Usuario::userToArray ( $usuarioLogueado );
						
					} catch ( BusinessLogicException $e1 ) {
						
						$_SESSION ["mensajes"] = $e1->__toString ();
					} catch ( DBTransactionException $e2 ) {
						
						$_SESSION ["mensajes"] = $e2->__toString ();
					}
					
				} else {
					
					$_SESSION ["mensajes"] = HelperModules::crearMensajeError ( "Tiene que rellenar los campos" );
				}
				
				HelperModules::redireccionarAlInicio ();
				break;
			
			case 'Crear Periodo' :
				
				try {
					
					$periodoNuevo = new Periodo ( 0, $_POST ['fechaInicio'], $_POST ['fechaFinal'], $_POST ['nombre'] );
					$periodoNuevo->setUsuario ( Usuario::arrayToUser ( $usuarioApp ) );
					$modeloHome->crearPeriodo ( $periodoNuevo );
					
					$_SESSION ["mensajes"] = HelperModules::crearMensajeExito ( "El periodo ha sido creado" );
					
				} catch ( BusinessLogicException $e1 ) {
					
					$_SESSION ["mensajes"] = $e1->__toString ();
				} catch ( DBTransactionException $e2 ) {
					
					$_SESSION ["mensajes"] = $e2->__toString ();
				}
				
				HelperModules::redireccionarAlInicio ();
				break;
		}
	}
} else {
	// Si no es un evento, entonces se imprime la informacion del modulo
	
	// Imprime el contenido, que depende del usuario
	if ($usuarioApp == "Visitante") {
		
		$vistaHome->imprimirHTML_UsuarioNoLogueado ();
	} else {
		
		try {
			
			$dtoHome = new DTOModuloHome ();
			$dtoHome->setListaDePeriodosDeUnUsuario ( $modeloHome->obtenerListaDePeriodosDeUnUsuario ( Usuario::arrayToUser ( $usuarioApp ) ) );
			$dtoHome->setListaNotasFuturo ( $modeloHome->obtenerNotasFuturas ( $usuarioApp ['email'] ) );
			$vistaHome->imprimirHTML_UsuarioLogueado ( $dtoHome );
			
		} catch ( BusinessLogicException $e1 ) {
			
			$_SESSION ["mensajes"] = $e1->__toString ();
		} catch ( DBTransactionException $e2 ) {
			
			$_SESSION ["mensajes"] = $e2->__toString ();
		}
	}
}