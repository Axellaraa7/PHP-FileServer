<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once(realpath(__DIR__."/../db/DBConnection.php"));
require_once(realpath(__DIR__."/../models/Blob.php"));
require_once(realpath(__DIR__."/../models/Email.php"));
require_once(realpath(__DIR__."/../models/Exception.php"));
require_once(realpath(__DIR__."/../models/PHPMailer.php"));
require_once(realpath(__DIR__."/../models/SMTP.php"));
$conexion = DBConnection::setInstance()->getConnection();
$blob = new Blob($conexion);
$phpMailObj = new PHPMailer(true);

if(!empty($_FILES)){
  //Using file_get_contents without addslashes-recomended
  if($_FILES["file"]["size"] > 2097152){
    $ban = false;
    $mensaje = "El tamaño de la imagen debe ser menor de 2MB";
  }else{
    $ban = $blob->insert($_POST["name"],file_get_contents($_FILES["file"]["tmp_name"]));
    $alert = "Hubo un error al registrar los datos";
    if($ban && isset($_POST["email"])){
      $to = $_POST["email"];
      $title = $_POST["name"];
      $mensaje = "Esta es una prueba de correo";
      // $from = "From: pruebascorreosserver@gmail.com";
      $emailObj = new Email($to,$title,$mensaje);
      // $alert = $emailObj->xamppEmail($to,$title,$msj,$from);
      //----//
      $emailObj->setMailer($phpMailObj);
      try{
        $emailObj->phpMailer(PHPMailer::ENCRYPTION_STARTTLS);
        $alert = $emailObj->getAlert();
      }catch(Exception $e){
        $alert = $e->getMessage()." ".$e->getLine();
      }
    }
  }

  //It is used when u dont use prepared statements-not recommended.
  // $open = fopen($_FILES["file"]["tmp_name"],"r");
  // $read = fread($open,$_FILES["file"]["size"]);
  // $ban = $blob->insertSlashes($conexion->quote($_POST["name"]),$conexion->quote($read));
  //echo $read;
}

if(isset($_POST["idDelete"])){
  $ban = $blob->delete($_POST["idDelete"]);
  $alert = ($ban) ? "Imagen: ".$_POST["idDelete"]." eliminada..." : "Error al eliminar la imagen";
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
      if(isset($ban) && isset($alert)) echo ($ban) ? 
      "<div class='alert blueAlert'>$alert</div>" :
      "<div class='alert redAlert'>$alert</div>"; 
      ?>
      <form action="" method="post" enctype="multipart/form-data">
        <div class="formGroup">
          <input type="text" name="name" id="name" class="inputText mdText white" required>
          <label for="name" class="labelText mdText white">Name of the file</label>
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
              <img src='data:image/jpeg;base64,".base64_encode($register["file"])."' alt='".$register["name"]."'>
            </figure>
            <p class='id-card white rgText'>".$register["id"].
            "</p>
            <p class='title-card white rgText'>".$register["name"]."
            </p>
            <form action='./' method='post' class='delete-card'>
              <input type='hidden' value='".$register["id"]."' name='idDelete'>
              <button type='submit' class='btn-card'>
                <svg>
                  <use xlink:href='./assets/trash.svg#trash'></use>
                </svg>
              </button>
            </form>
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