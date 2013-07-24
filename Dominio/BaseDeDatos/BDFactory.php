<?php

namespace Dominio\BaseDeDatos;

$_SERVER['DOCUMENT_ROOT'] = 'C:/xampp/htdocs/Qualify';

require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/BaseDeDatos/BDMySQL.php';

class BDFactory {
	
	public static function crearManejadorBD()
	{
		$BD = new BDMySQL();
 		return $BD;
	}
	
}
