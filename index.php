<!DOCTYPE html>
<html>
<head>
<meta name="viewport"
	content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1 ">
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>Qualify.com</title>
<link rel="shortcut icon" href="http://ProjectPHP/media/img/favicon.png">
<link href='http://fonts.googleapis.com/css?family=Coda&subset=latin'
	rel='stylesheet' type='text/css'>
<link rel="stylesheet"
	href="http://ProjectPHP/media/css/reset-layout.css" type="text/css" />
<link rel="stylesheet" href="http://ProjectPHP/media/css/bootstrap.css"
	type="text/css" />
<link rel="stylesheet" href="http://ProjectPHP/media/css/jquery-ui.css"
	type="text/css" />
<link rel="stylesheet" href="http://ProjectPHP/media/css/template.css"
	type="text/css" />
<script src="http://ProjectPHP/media/js/jquery.js"></script>
<script src="http://ProjectPHP/media/js/bootstrap.js"></script>
<script src="http://ProjectPHP/media/js/script.js"></script>
<script src="http://ProjectPHP/media/js/jquery-ui.js"></script>
<script src="http://ProjectPHP/media/js/validation/jQuery.validate.js"></script>
<script src="http://malsup.github.com/jquery.form.js"></script>
<script
	src="http://ProjectPHP/media/js/validation/additional-methods.js"></script>
<script src="http://ProjectPHP/media/js/validation/messages.js"></script>

</head>
<?php
use Dominio\Clases\Usuario;
require_once 'Dominio/Clases/Usuario.php';

session_start();
if (!isset($_SESSION["usuario"])) {
	$_SESSION["usuario"] = "Visitante";
	$_SESSION["id"] = null;
}

?>
<body>

	<!-- Contenedor Global -->
	<div id="contenedorGlobal" class="row-fluid">

		<!-- Contenedor Lateral (Argollas) -->
		<div id="divArgollas"></div>

		<!-- Contenedor Lateral (Cuaderno) -->
		<div id="contenedorCuaderno">

			<!-- Header -->

			<?php
			require_once 'modules/Header/ControladorHeader.php';
			?>

			<!-- Fin header -->

			<!-- Contenedor con la informacion de la app -->
			<div class="row-fluid">
				<div id="contenedorInformacion" class="span12">
					<div class="row-fluid">
						<!-- Aqui va el contenido generado por php -->

						<?php
						$controlador = "";
						$usuarioApp = $_SESSION["usuario"];

						if($usuarioApp == 'Visitante'){
							require_once 'modules/Home/ControladorHome.php';
						}else{
							if(isset ($_GET['section'])){
								$controlador = strtolower($_GET['section']);
								switch($controlador){

									case 'home':
										require_once 'modules/Home/ControladorHome.php';
										break;

									case 'periodo':
										if(isset ($_GET['id'])){
											$_SESSION["idPeriodo"] = $_GET['id'];
											require_once 'modules/Periodo/ControladorPeriodo.php';
										}else{
											//Llamar a error
										}
										break;

									case 'perfil':
										break;

									case 'buscarpersonas':
										break;

									default:
										echo "error";
										break;
								}
							}else{
								require_once 'modules/Home/ControladorHome.php';
							}
						}

						if (isset($_SESSION["mensajes"])) {
							echo $_SESSION["mensajes"];
						}
						$_SESSION["mensajes"] = '';
						?>

						<!-- Termina el contenido generado por php -->
					</div>
				</div>
			</div>

			<!-- Footer -->
			<div class="row-fluid">
				<div id="footer" class="span12"></div>
			</div>
			<!-- Fin Footer -->
		</div>
	</div>

</body>
</html>
