<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
require '../vendor/autoload.php';

class Mail {

    public $from = "pandayg0@gmail.com";
    public $frompass = "9320102989";

    function sendMail($to, $subject, $body, $cc = "") {
        $mail = new PHPMailer;
        $mail->isSMTP();                            // Set mailer to use SMTP
        $mail->SMTPDebug = 2;
        $mail->Host = 'smtp.gmail.com';             // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                     // Enable SMTP authentication
        $mail->Username = $this->from;              // SMTP username
        $mail->Password = $this->frompass;            // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;

// TCP port to connect to
        $mail->setFrom($this->from, 'info@buyonemi.in');
//        $mail->addReplyTo($to, 'CodexWorld');
        $mail->addAddress($to);
        if ($cc == "" || $cc == NULL || $cc == " ") {
            
        } else {
            $ccarr = explode(",", $cc);
            for ($i = 0; $i < count($ccarr); $i++) {
                $mail->addCC($ccarr[$i]);
            }
        }
//        $mail->addBCC('pandayg0@gmail.com');
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        if (!$mail->send()) {
//            echo 'Message could not be sent.';
            return 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return "1";
        }
    }

}
