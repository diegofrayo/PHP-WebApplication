<?php

namespace domain\database;

require_once 'BDMySQL.php';

class BDFactory {
	
	public static function crearManejadorBD()
	{
		$BD = new BDMySQL();
 		return $BD;
	}
	
}
