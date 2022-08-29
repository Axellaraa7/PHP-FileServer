<?php 
class Blob{
  
  public function __construct($conexion){
    $this->conexion = $conexion;
    $this->table = "blob_mailing";
  }

  public function getAll(){
    return $this->conexion->query("SELECT * FROM $this->table");
  }

  public function insert($name,$blob){
    $query = $this->conexion->prepare("INSERT INTO $this->table (name,file) values (?,?)");
    return $query->execute(array($name,$blob));
  }

  /* public function insertSlashes($name,$blob){
    $ban = $this->conexion->exec("INSERT INTO $this->table (name,file) values (".$name.",".$blob.")");
    return ($ban) ? true : false;
  } */

  public function delete($id){
    $query = $this->conexion->prepare("DELETE FROM $this->table WHERE id = ?");
    return $query->execute(array($id));
  }
}
?>