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
    <section class="formContainer">
      <?php 
      if(isset($ban)) echo ($ban) ? "<div class='alert blueAlert'>$mensaje</div>" :
        "<div class='alert redAlert'>$mensaje</div>"; 
      ?>
      <form action="" method="post" enctype="multipart/form-data">
        <div class="formGroup">
          <input type="text" name="imgName" id="imgName" class="inputText mdText white" required>
          <label for="imgName" class="labelText mdText white">Name of the file</label>
        </div>
        <div class="formGroup">
          <input type="email" name="email" id="email" class="inputText mdText white" required> 
          <label for="email" class="labelText mdText white">Email</label>
        </div>
        <div class="formGroup">
          <input type="file" name="file" id="file" class="inputFile">
          <label for="file" class="mdText white"><img src="./assets/file.svg" alt="file icon"><img src="./assets/upload.svg" alt="upload icon"></label>
        </div>
        <div class="formGroup">
          <button type="submit" class="btn btnMain">Enviar</button>
          <button type="reset" class="btn btnSec">Borrar</button>
        </div>
      </form>
    </section>
    
    
    <?php if($registers->rowCount() > 0){ ?>
    <section class="cardsContainer">
      <h2 class="white bgText bold">Current Registers</h2>
      <div class="cards">
      <?php 
        foreach($registers as $register){
          echo "
          <div class='card'>
            <figure class='img-card'>
              <img src='data:image/jpeg;base64,".base64_encode($register["file"])."' alt='".$register["name_blob"]."'>
            </figure>
            <p class='id-card white rgText'>".$register["id"].
            "</p>
            <p class='title-card white rgText'>".$register["name_blob"]."
            </p>
          </div>
          ";
        }
      ?>
      </div>
    </section>
    <?php } ?>
  </main>
</body>
</html>