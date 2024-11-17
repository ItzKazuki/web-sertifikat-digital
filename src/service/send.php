<?php

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

// include 'DotEnv.php'; // enable this for devlopment only!

(new DotEnvEnvironment)->load(__DIR__ . '/../../');

require_once 'PHPmailer/src/PHPMailer.php';
require_once 'PHPmailer/src/SMTP.php';
require_once 'PHPmailer/src/Exception.php';

class MailSender
{
    private $mail;

    // Define a constant for reset email
    public static $resetPassword = 'reset_password.html';
    public static $successRegister = 'success_register.html';
    public static $createNewCertificate = 'get_new_certificate.html';
    public static $createNewAccount = 'success_create_account.html';

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->isHTML(true);
        $this->mail->SMTPAuth = true;
        $this->mail->SMTPSecure = 'ssl';
        $this->mail->Host = $_ENV['SMTP_HOST'];
        $this->mail->Port = $_ENV['SMTP_PORT'];
        $this->mail->Username = $_ENV['SMTP_USER'];
        $this->mail->Password = $_ENV['SMTP_PASSWORD'];
        $this->mail->setFrom($_ENV['SMTP_USER'], 'Digicert');
    }

    public function sendMail(string $to, string $name, string $subject, array $content, $type = null, $attachment = null)
    {
        $this->mail->addAddress($to, $name);
        $this->mail->Subject = $subject;

        // Load the template file
        $templatePath = __DIR__ . "/template/$type";
        if(!is_null($attachment)) {
            $this->mail->addAttachment($attachment['file_url'], $attachment['file_name']);
        }
        if (file_exists($templatePath)) {
            // Load the template content
            $body = file_get_contents($templatePath);
            // Replace placeholders with actual values
            foreach ($content as $key => $value) {
                $body = str_replace('{{ $' . $key . ' }}', $value, $body);
            }
            // Set the body of the email
            $this->mail->Body = $body;
        } else {
            throw new Exception("Template file not found: " . $templatePath);
        }

        // Send the email
        if (!$this->mail->send()) {
            return false; // Email not sent
        }

        return true; // Email sent successfully
    }
}
