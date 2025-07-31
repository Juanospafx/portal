<?php
class Database {
    public static $db;
    public static $con;

    function __construct(){
        $this->user = "brightro_wp625";  // Usuario de la base de datos
        $this->pass = "name1234";  // Contraseña del usuario de la base de datos
        $this->host = "localhost";  // Según DirectAdmin
        $this->ddbb = "brightro_katalog";  // Nombre de la base de datos
    }

    function connect(){
        $con = new mysqli($this->host, $this->user, $this->pass, $this->ddbb);
        if ($con->connect_error) {
            die("Error de conexión: " . $con->connect_error);
        }
        return $con;
    }

    public static function getCon(){
        if(self::$con == null && self::$db == null){
            self::$db = new Database();
            self::$con = self::$db->connect();
        }
        return self::$con;
    }
}
?>
