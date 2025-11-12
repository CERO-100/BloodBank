<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require_once __DIR__ . '/../phpmailer/Exception.php';
require_once __DIR__ . '/../phpmailer/PHPMailer.php';
require_once __DIR__ . '/../phpmailer/SMTP.php';

/**
 * Send Email
 * @param string $to      Recipient email
 * @param string $subject Email subject
 * @param string $body    Email body (HTML allowed)
 * @return bool           True if sent, false otherwise
 */
function send_mail($to, $subject, $body)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                        // Use SMTP
        $mail->Host       = 'smtp.gmail.com';                  // SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'bloodbankpypa@gmail.com';           // SMTP username
        $mail->Password   = '@@@bloodbank@@@123';              // SMTP password (app password recommended)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        //Recipients
        $mail->setFrom('bloodbankpypa@gmail.com', 'Blood Bank System');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}
