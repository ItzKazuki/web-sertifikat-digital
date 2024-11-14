<?php

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

(new DotEnvEnvironment)->load(__DIR__ . '/../../');

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
  $mail->Host = $_ENV['SMTP_HOST'];
  $mail->Port = $_ENV['SMTP_PORT'];
  $mail->SMTPAuth = true;
  $mail->Username = $_ENV['SMTP_USER'];
  $mail->Password = $_ENV['SMTP_PASSWORD'];
  $mail->setFrom($_ENV['SMTP_USER'], 'Digicert');
  // $mail->addReplyTo($_ENV['SMTP_USER'], 'Digicert');
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
