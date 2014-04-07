<?php

namespace domain\factory;

use domain\daos_mysql\DaoAsignatura;
use domain\daos_mysql\DaoPeriodo;
use domain\daos_mysql\DaoUsuario;
use domain\daos_mysql\DaoNota;
use domain\daos_mysql\DaoGrupoDeNotas;

require_once $_SERVER['DOCUMENT_ROOT'].'/domain/daos_mysql/DaoUsuario.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/domain/daos_mysql/DaoPeriodo.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/domain/daos_mysql/DaoAsignatura.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/domain/daos_mysql/DaoNota.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/domain/daos_mysql/DaoGrupoDeNotas.php';

class DaoFactory {
	
	public static function newDaoAsignatura() {
		
		return new DaoAsignatura();
	}
	
	public static function newDaoPeriodo() {
	
		return new DaoPeriodo();
	}
	
	public static function newDaoUsuario() {
	
		return new DaoUsuario();
	}
	
	public static function newDaoNota() {
	
		return new DaoNota();
	}
	
	public static function newDaoGrupoDeNotas() {
	
		return new DaoGrupoDeNotas();
	}
	
}