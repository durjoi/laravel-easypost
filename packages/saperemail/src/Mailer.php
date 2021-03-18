<?php

namespace Saperemarketing\Phpmailer;

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{

  public function sendEmail($emails, $subject, $content)
  {
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();                                            
        $mail->Host       = config('saperemail.host');
        $mail->SMTPAuth   = config('saperemail.auth');
        $mail->Username   = config('saperemail.username');
        $mail->Password   = config('saperemail.password');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = config('saperemail.port');

        //Recipients
        $mail->setFrom(config('saperemail.from_email'), config('saperemail.from_name'));

        if(is_array($emails)){
          foreach ($emails as $email) {
            $mail->addAddress($email);
          }
        } else {
          $mail->addAddress($emails);
        }
        $mail->addReplyTo(config('saperemail.from_email'), config('saperemail.from_name'));

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $content;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
  }

}