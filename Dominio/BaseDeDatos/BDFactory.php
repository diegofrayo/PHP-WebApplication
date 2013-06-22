<?php

namespace Dominio\BaseDeDatos;
require_once $_SERVER['DOCUMENT_ROOT'].'/Dominio/BaseDeDatos/BDMySQL.php';

class BDFactory {
	
	public static function crearManejadorBD()
	{
		$BD = new BDMySQL();
 		return $BD;
	}
	
}
