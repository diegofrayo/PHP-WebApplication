<?php

session_start();

//$_SESSION["root"] = "C:/xampp/htdocs/ProjectPHP";

//$root = dirname($_SERVER['SCRIPT_NAME']);

if (!isset($_SESSION["usuario"])) {
	$_SESSION["usuario"] = "Visitante";
}

?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport"
	content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1 ">
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>Qualify.com</title>
<link rel="shortcut icon"
	href="http://qualify.hol.es/media/img/favicon.png">
<link href='http://fonts.googleapis.com/css?family=Coda&subset=latin'
	rel='stylesheet' type='text/css'>
<link rel="stylesheet"
	href="http://qualify.hol.es/media/css/reset-layout.css" type="text/css" />
<link rel="stylesheet"
	href="http://qualify.hol.es/media/css/bootstrap.css" type="text/css" />
<link rel="stylesheet"
	href="http://qualify.hol.es/media/css/bootstrap-responsive.css"
	type="text/css" />
<link rel="stylesheet"
	href="http://qualify.hol.es/media/css/datepicker.css">
<link rel="stylesheet"
	href="http://qualify.hol.es/media/css/template.css" type="text/css" />

<script src="http://qualify.hol.es/media/js/jquery.js"></script>
<script src="http://qualify.hol.es/media/js/bootstrap.js"></script>
<script src="http://qualify.hol.es/media/js/bootstrap-datepicker.js"></script>
<script	src="http://qualify.hol.es/media/js/validation/validate.js"></script>
<script src="http://qualify.hol.es/media/js/script.js"></script>

</head>

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
							if(isset ($_GET['section'])){
								$controlador = strtolower($_GET['section']);
								switch($controlador){
									case 'home':
										require_once 'modules/Home/ControladorHome.php';
										break;

									default:
										require_once 'error404.html';
										break;
								}
							}else{
								require_once 'modules/Home/ControladorHome.php';
							}
						}else{
							if(isset ($_GET['section'])){
								$controlador = strtolower($_GET['section']);

								switch($controlador){

									case 'home':
										require_once 'modules/Home/ControladorHome.php';
										break;

									case 'periodo':
										if(isset ($_GET['id'])){
											require_once 'modules/Periodo/ControladorPeriodo.php';
										}else{
											require_once 'error404.html';
										}
										break;

									case 'perfil':
										break;

									case 'buscarpersonas':
										break;

									default:
										require_once 'error404.html';
										break;
								}
							}else{
								require_once 'modules/Home/ControladorHome.php';
							}
							echo "<script>modificarTituloApp('".$usuarioApp['nick']."');</script>";
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
