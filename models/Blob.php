<?php 
class Blob{
  
  public function __construct($conexion){
    $this->conexion = $conexion;
    $this->table = "usingblob";
  }

  public function getAll(){
    return $this->conexion->query("SELECT * FROM $this->table");
  }

  public function insert($name,$blob){
    $query = $this->conexion->prepare("INSERT INTO $this->table (name_blob,file) values (?,?)");
    $ban = $query->execute(array($name,$blob));
    return $ban;
  }

  public function insertSlashes($namee,$blob){
    $ban = $this->conexion->exec("INSERT INTO $this->table (name_blob,file) values (".$namee.",".$blob.")");
    return ($ban) ? true : false;
  }
}
?>