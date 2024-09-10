<?php
class Database{
    private static $instance = null;

    private $connection;

    private function __construct(){
        // constructor privado para evitar instancia fuera de la clase
        try{
            $this->connection = new PDO("mysql:host=localhost;dbname=api_example", "root", "");

            $this->connection->exec("SET NAMES 'utf8'");

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(Exception $e){
            die("No se conectó a la base de datos: ". $e->getMessage());
        }
        
    }

    public static function getInstance(){
        if(self::$instance == null){
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function getConnection(){
        return $this->connection;
    }
}