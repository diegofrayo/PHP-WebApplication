<?php
namespace domain\database;
use domain\dto\DTOCrud;

require_once 'IBaseDeDatos.php';

class BDMySQL implements IBaseDeDatos
{

    const USUARIO = '';

    const CLAVE = '';

    const BD = '';

    const SERVIDOR = '';

    /**
     * Conexion a base de exitoConsulta
     *
     * @var PDO
     */
    private $_conexion;

    public function conectar ()
    {
        $this->_conexion = new \PDO(
                "mysql:host=" . self::SERVIDOR . ";dbname=" . self::BD,
                self::USUARIO, self::CLAVE,
                array(
                        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES  \'UTF8\''
                ));

        if ($this->_conexion == false) {

            throw new \Exception("Error al conectarse a la base de datos");
        }
    }

    public function desconectar ()
    {
        $this->_conexion = null;
    }

    public function obtenerDatos ($consultaSQL, $arrayDatos)
    {
        $this->conectar();
        $sentencia = $this->_conexion->prepare($consultaSQL);
        $sentencia->execute($arrayDatos);
        $resultados = $sentencia->fetchAll(\PDO::FETCH_ASSOC);
        $this->desconectar();

        return $resultados;
    }

    public function editar ($consultaSQL, $arrayDatos)
    {
        $this->conectar();
        $sentencia = $this->_conexion->prepare($consultaSQL);
        $exitoConsulta = $sentencia->execute($arrayDatos);
        $this->desconectar();

        return $exitoConsulta;
    }

    public function eliminar ($consultaSQL, $arrayDatos)
    {
        $this->conectar();
        $sentencia = $this->_conexion->prepare($consultaSQL);
        $exitoConsulta = $sentencia->execute($arrayDatos);
        $this->desconectar();

        return $exitoConsulta;
    }

    public function insertar ($consultaSQL, $arrayDatos)
    {
        $this->conectar();
        $sentencia = $this->_conexion->prepare($consultaSQL);
        $exitoConsulta = $sentencia->execute($arrayDatos);
        $DTO = new DTOCrud($exitoConsulta);

        if ($exitoConsulta) {

            $ultimoId = $this->_conexion->lastInsertId();
            $DTO->setUltimoId($ultimoId);
        }

        $this->desconectar();

        return $DTO;
    }

    public function obtenerConexionPDO ()
    {
        return $this->_conexion;
    }
}