<?php 
class Email{
  private $email, $to, $title, $msj, $credentials, $alert;

  public function setMailer($setter){
    $this->email = $setter;
  }

  public function __construct($to, $title, $msj){
    $this->to = $to;
    $this->title = $title;
    $this->msj = $msj;  
    $this->credentials = require_once(realpath(__DIR__."/../smtp.php"));
  }

  public function xamppEmail(){
    return (mail($this->to,$this->title,$this->msj,$this->credentials["from"])) ? "Datos registros, mensaje enviado" : "Error al enviar el mensaje";
  }

  public function phpMailer($smtpSecure){
    $this->email->isSMTP();
    $this->email->Host = "smtp.gmail.com";
    $this->email->SMTPAuth = true;
    $this->email->Username = $this->credentials["from"];
    $this->email->Password = $this->credentials["psw"];
    $this->email->SMTPSecure = $smtpSecure;
    $this->email->Port = 587;
    $this->email->setFrom($this->credentials["from"], 'PruebasCorreos');
    $this->email->addAddress($this->to); 
    $this->email->isHTML(true);
    $this->email->Subject = $this->title;
    $this->email->Body    = $this->msj;
    $this->email->AltBody = 'This is the body in plain text for non-HTML mail clients';
    $this->email->send();
    $this->alert = "Datos registros, mensaje enviado";
  }

  public function getAlert(){
    return $this->alert;
  }
}

?>