<?php

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require_once 'PHPmailer/src/PHPMailer.php';
require_once 'PHPmailer/src/SMTP.php';
require_once 'PHPmailer/src/Exception.php';

function sendMail($to, $name, $subject, $message)
{
  $mail = new PHPMailer(true);
  $mail->isSMTP();
  $mail->SMTPAuth = true;
  $mail->SMTPSecure = 'ssl';
  // $mail->SMTPDebug = SMTP::DEBUG_CONNECTION;
  $mail->Host = 'digicert.beomzuki.my.id';
  $mail->Port = 465;
  $mail->SMTPAuth = true;
  $mail->Username = 'no-reply@digicert.beomzuki.my.id';
  $mail->Password = 'fUe8@FZ006*J';
  $mail->setFrom('no-reply@digicert.beomzuki.my.id', 'Digicert');
  // $mail->addReplyTo('no-reply@digicert.beomzuki.my.id', 'Digicert');
  $mail->addAddress("$to", "$name");
  $mail->Subject = $subject;
  // $mail->msgHTML(file_get_contents('message.html'), __DIR__);
  $mail->Body = $message;

  //$mail->addAttachment('test.txt');

  if (!$mail->send()) {
    return false;
  }

  return true;
}
