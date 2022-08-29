<?php
class DBConnection{
  private static $instance;
  private $dbInfo, $connection;

  public static function setInstance(){
    if(!isset(self::$connection)){
      self::$instance = new DBConnection();
    }
    return self::$instance;
  }

  public function getConnection(){
    return $this->connection;
  }

  private function __construct(){
    $this->dbInfo = require_once(realpath(__DIR__."/../config.php"));
    $this->setConnection();
  }

  private function setConnection(){
    try{
      $this->connection = new PDO("mysql:host=".
      $this->dbInfo["host"].
      ";dbname=".$this->dbInfo["dbname"]
      ,$this->dbInfo["user"],$this->dbInfo["psw"]);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->connection->exec("set names ".$this->dbInfo["charset"]);
    }catch(PDOException $ex){
      $this->connection = false;
      echo "Error al conectar a la base de datos".$ex->getMessage();
    }
  }
}
?>