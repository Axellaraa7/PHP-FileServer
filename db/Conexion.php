<?php
class Conexion{
  private static $conexion;

  public static function conexion($dbname){
    try{
      self::$conexion = new PDO("mysql:host=127.0.0.1;dbname=$dbname","root","");
      self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      self::$conexion->exec("set names utf8");
    }catch(PDOException $ex){
      self::$conexion = false;
      echo "Error al conectar a la base de datos".$ex->getMessage();
    }finally{
      return self::$conexion;
    }
  }
}
?>