<?php 
class FileServer{
  
  public function __construct($conexion){
    $this->conexion = $conexion;
    $this->table = "server_mailing";
  }

  public function getAll(){
    return $this->conexion->query("SELECT * FROM $this->table",MYSQLI_ASSOC);
  }

  public function insert($data){
    $query = $this->conexion->prepare("INSERT INTO $this->table (name,file_url) values (?,?)");
    return $query->execute($data);
  }

  public function delete($id){
    $query = $this->conexion->prepare("DELETE FROM $this->table WHERE id = ?");
    return $query->execute(array($id));
  }
}
?>