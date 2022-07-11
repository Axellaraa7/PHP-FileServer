<?php
require_once("./db/Conexion.php");
require_once("./models/Blob.php");
$conexion = Conexion::conexion("pruebasconexion");
$blob = new Blob($conexion);

if(!empty($_FILES)){
  //Using file_get_contents without addslashes-recomended
  if($_FILES["file"]["size"] > 2097152){
    $ban = false;
    $mensaje = "El tamaño de la imagen debe ser menor de 2MB";
  }else{
    $ban = $blob->insert($_POST["imgName"],file_get_contents($_FILES["file"]["tmp_name"]));
    $mensaje = "Hubo un error al registrar los datos";
    if($ban && isset($_POST["email"])){
      $to = $_POST["email"];
      $title = $_POST["imgName"];
      $mensaje = "Esta es una prueba de correo";
      $from = "From: pruebascorreosserver@gmail.com";
      $mensaje = (mail($to,$title,$mensaje,$from)) ? "Datos registrados, mensaje enviado" : "Error al enviar el mensaje";
    }
  }

  //It is used when u dont use prepared statements-not recommended.
  // $open = fopen($_FILES["file"]["tmp_name"],"r");
  // $read = fread($open,$_FILES["file"]["size"]);
  // $ban = $blob->insertSlashes($conexion->quote($_POST["imgName"]),$conexion->quote($read));
  //echo $read;

}

$registers = $blob->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./css/index.css">
  <title>FileUpdating-Mailing</title>
</head>
<body>
  <header>
    <h1>FileUpdating-Mailing</h1>
  </header>
  <main>
    <?php 
    if(isset($ban)) echo ($ban) ? "<div class='alert blueAlert'>$mensaje</div>" :
      "<div class='alert redAlert'>$mensaje</div>"; 
    ?>
    <form action="" method="post" enctype="multipart/form-data">
      <div class="formGroup">
        <label for="imgName">Name of the file</label>
        <input type="text" name="imgName" id="imgName">
      </div>
      <div class="formGroup">
        <label for="email">Email</label>
        <input type="email" name="email" id="email">
      </div>
      <div class="formGroup">
        <input type="file" name="file" id="file">
      </div>
      <div class="formGroup">
        <button type="submit" class="btn btnMain">Enviar</button>
        <button type="reset" class="btn btnSec">Borrar</button>
      </div>
    </form>
    <?php if($registers->rowCount() > 0){ ?>
    <section class="tableRegisters">
      <table>
        <thead>
          <tr>
            <th>Id</th>
            <th>Name</th>
            <th>File</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          foreach($registers as $register){
            echo "<tr>";
            echo "<td>".$register["id"]."</td><td>".$register["name_blob"]."</td><td><img alt='img' width='200px' height='200px' src='data:image/jpeg;base64,".base64_encode($register["file"])."'></td>";
            echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </section>
    <?php } ?>
  </main>
</body>
</html>