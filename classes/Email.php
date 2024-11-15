<?php

namespace Classes;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email {

    public $email;
    public $nombre;
    public $token;


    public function __construct($email, $nombre, $token){
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion (){
        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = 'e2ad1a6efeb8f9';
        $mail->Password = '6741aec5ffe490';
        $mail->Port = '465';
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com');
        $mail->Subject= "Confirma tu cuenta";

        $contenido = "<html>";
        $contenido.= "<p><strong> Hola " . $this->nombre .   " </strong> Necesitas confirmar tu cuenta de correo electrónico, para finalizar el registro da click al siguiente enlace</p>";
        $contenido.= "<p> Presiona aquí: <a href='http://localhost:3000/confirmar?token=". $this->token ."'> Confirmar Cuenta </a> </p>";
        $contenido.= "<p> Si no solicitaste esta cuenta, puedes ignorar el mensaje   </p>";
        $contenido.= "</html>";

        $mail->Body = $contenido;

        $mail->send();

    }

    public function enviarInstrucciones (){
        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = 'e2ad1a6efeb8f9';
        $mail->Password = '6741aec5ffe490';
        $mail->Port = '465';
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com');
        $mail->Subject= "Restablece tu contraseña";

        $contenido = "<html>";
        $contenido.= "<p><strong> Hola " . $this->nombre .   " </strong> Solicitaste restablecer tu contraseña, para continuar con el proceso da click al siguiente enlace</p>";
        $contenido.= "<p> Presiona aquí: <a href='http://localhost:3000/restablecer?token=". $this->token ."'> Restablecer contraseña </a> </p>";
        $contenido.= "<p> Si no solicitaste esto, puedes ignorar el mensaje   </p>";
        $contenido.= "</html>";

        $mail->Body = $contenido;

        $mail->send();
    }
}