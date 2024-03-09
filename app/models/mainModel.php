<?php

namespace app\models;

use \PDO;

if (file_exists(__DIR__ . "/../../config/server.php")) {
    require_once __DIR__ . "/../../config/server.php";
}

class mainModel
{

    private $server = DB_SERVER;
    private $db = DB_NAME;
    private $user = DB_USER;
    private $pass = DB_PASS;

    /*----------  Funcion de conexion a Base de Datos ----------*/
    protected function conectar()
    {
        $conexion = new PDO("mysql:host=" . $this->server . ";dbname=" . $this->db, $this->user, $this->pass);
        $conexion->exec("SET CHARACTER SET utf8");
        return $conexion;
    }

    /*----------  Funcion para realizar consultas ----------*/
    protected function ejecutarConsulta($consulta)
    {
        $sql = $this->conectar()->prepare($consulta);
        $sql->execute();
        return $sql;
    }

    /*---------- Funcion para validar los datos ----------*/
    protected function validarDatos($filtro, $cadena)
    {
        if (preg_match("/^" . $filtro . "$/", $cadena)) {
            return false;
        } else {
            return true;
        }
    }

    /*----------  Funcion para insertar Datos  ----------*/
    protected function insertarDatos($tabla, $datos)
    {

        $query = "INSERT INTO $tabla (";

        $i = 0;
        foreach ($datos as $clave) {
            if ($i >= 1) {$query .= ",";}
            $query .= $clave["campo_nombre"];
            $i++;
        }

        $query .= ") VALUES(";

        $i = 0;
        foreach ($datos as $clave) {
            if ($i >= 1) {$query .= ",";}
            $query .= $clave["campo_marcador"];
            $i++;
        }
        $query .= ")";
        $sql = $this->conectar()->prepare($query);

        foreach ($datos as $clave) {
            $sql->bindParam($clave["campo_marcador"], $clave["campo_valor"]);
        }

        $sql->execute();

        return $sql;
    }

    /*---------- Funcion seleccionar datos ----------*/
    public function seleccionarDatos($tabla, $campo)
    {
        $sql = $this->conectar()->prepare("SELECT $campo FROM $tabla");
        $sql->execute();

        return $sql;
    }

    /*----------  Funcion para actualizar datos  ----------*/
    protected function actualizarDatos($tabla, $datos, $condicion)
    {

        $query = "UPDATE $tabla SET ";

        $i = 0;
        foreach ($datos as $clave) {
            if ($i >= 1) {$query .= ",";}
            $query .= $clave["campo_nombre"] . "=" . $clave["campo_marcador"];
            $i++;
        }

        $query .= " WHERE " . $condicion["condicion_campo"] . "=" . $condicion["condicion_marcador"];

        $sql = $this->conectar()->prepare($query);

        foreach ($datos as $clave) {
            $sql->bindParam($clave["campo_marcador"], $clave["campo_valor"]);
        }

        $sql->bindParam($condicion["condicion_marcador"], $condicion["condicion_valor"]);

        $sql->execute();

        return $sql;
    }

    /*---------- Funcion para eliminar datos ----------*/
    protected function eliminarDatos($tabla, $campo, $id)
    {
        $sql = $this->conectar()->prepare("DELETE FROM $tabla WHERE $campo=:id");
        $sql->bindParam(":id", $id);
        $sql->execute();

        return $sql;
    }

}
