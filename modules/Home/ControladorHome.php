<?php

use Dominio\Clases\Foto;

use Dominio\DTO\DTOModuloHome;

use Dominio\Excepciones\DBTransactionException;
use Dominio\Clases\Periodo;
use modules\HelperModules;
use Dominio\Excepciones\BusinessLogicException;
use modules\Home\VistaHome;
use Dominio\Clases\Usuario;
use modules\Home\ModeloHome;

$_SERVER['DOCUMENT_ROOT'] = 'C:/xampp/htdocs/Qualify';

require_once $_SERVER['DOCUMENT_ROOT'].'/modules/Home/VistaHome.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/Home/ModeloHome.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Clases/Usuario.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Clases/Foto.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Excepciones/BusinessLogicException.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/Excepciones/DBTransactionException.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/HelperModules.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/DTO/DTOModuloHome.php';

$modeloHome = new ModeloHome();
$vistaHome = new VistaHome();
//$usuarioApp = $_SESSION["usuario"];


//Si se pulsó algun boton
if(isset($_POST["action"])){

	$action = $_POST["action"];
	session_start();
	$usuarioApp = $_SESSION["usuario"];

	//Si el evento, es ajax
	if(isset($_POST["ajax"])){

		switch ($action){

			case 'Comprobar Nick':

				try{
					$estaDisponible = $modeloHome->comprarDisponibilidadNick($_POST['nick']);
					if($estaDisponible == true){
						echo "Disponible";
					}else{
						echo "No Disponible";
					}

				}catch (BusinessLogicException $e1){
					$_SESSION["mensajes"] = $e1->__toString();

				}catch (DBTransactionException $e2){
					$_SESSION["mensajes"] = $e2->__toString();
				}

				break;

			case 'Comprobar Email':

				try{
					$usuario = $modeloHome->comprarDisponibilidadEmail($_POST['email']);
					if($usuario==null){
						echo "Disponible";
					}else{
						echo "No Disponible";
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

			case 'Registrar':
				try{
					$usuarioRegistrado = new Usuario($_POST["email"],$_POST["nick"], $_POST["nombre"], $_POST["password"]);

					//Se genera el id unico para la foto
					$tiempoEnMili = microtime();
					$tiempoEnMili = substr($tiempoEnMili, 0,9);
					$idFoto = md5($tiempoEnMili);
					$idFoto = substr($idFoto, 0,9);
					$configuracionFoto = "";
					$tipoImagen = "";

					//Si el usuario subió su foto
					if(count($_FILES['foto']['name'])){
						$configuracionFoto = "nueva";
						$tipoImagen = $_FILES['foto']['type'];
						$tipoImagen = explode("/", $tipoImagen);
						$tipoImagen = ".".$tipoImagen[1];
					}else{
						$configuracionFoto = "defecto";
						$tipoImagen = ".png";
					}

					//Creo la foto
					$foto = new Foto($idFoto, "public/avatar/".$idFoto.$tipoImagen);
					$modeloHome->crearFoto($foto, $configuracionFoto);

					//Creo el usuario
					$usuarioRegistrado->setFoto($foto);
					$usuarioRegistrado = $modeloHome->registrarUsuario($usuarioRegistrado);

					$_SESSION["usuario"] = Usuario::userToArray($usuarioRegistrado);
					$_SESSION["mensajes"] = HelperModules::crearMensajeExito("Se ha registrado");

				}catch (BusinessLogicException $e1){
					$_SESSION["mensajes"] = $e1->__toString();
					$modeloHome->borrarFoto($foto);
				}catch (DBTransactionException $e2){
					$_SESSION["mensajes"] = $e2->__toString();
					$modeloHome->borrarFoto($foto);
				}
				HelperModules::redireccionarAlInicio();
				break;

			case 'Login':
				if($_POST["email"] != '' && $_POST["password"]!=''){
					try{
						$usuarioLogueado = $modeloHome->iniciarSesionUsuario($_POST["email"], $_POST["password"]);
						$_SESSION["usuario"] = Usuario::userToArray($usuarioLogueado);
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
					$periodoNuevo = new Periodo(0, $_POST['fechaInicio'], $_POST['fechaFinal'], $_POST['nombre']);
					$periodoNuevo->setUsuario(Usuario::arrayToUser($usuarioApp));
					$modeloHome->crearPeriodo($periodoNuevo);
					$_SESSION["mensajes"] = HelperModules::crearMensajeExito("El periodo ha sido creado");
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

	//Imprime el contenido, que depende del usuario
	if($usuarioApp == "Visitante"){
		$vistaHome->imprimirHTML_UsuarioNoLogueado();
	}else{
		try{
			$dtoHome = new DTOModuloHome();
			//$dtoHome->setFeedNoticias($feedNoticias);
			$dtoHome->setListaDePeriodosDeUnUsuario($modeloHome->obtenerListaDePeriodosDeUnUsuario(Usuario::arrayToUser($usuarioApp)));
			$dtoHome->setListaNotasFuturo($modeloHome->obtenerNotasFuturas($usuarioApp['nick']));
			$vistaHome->imprimirHTML_UsuarioLogueado($dtoHome);
		}catch (BusinessLogicException $e1){
			$_SESSION["mensajes"] = $e1->__toString();
		}catch (DBTransactionException $e2){
			$_SESSION["mensajes"] = $e2->__toString();
		}
	}
}