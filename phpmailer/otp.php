<?php
define('DIR', '');
require_once DIR . '../config.php';
$contol = new Controller();
$admin = new Admin();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    //Server settings Signed out



    $e = $_SESSION['email'];    
    $n = $_SESSION['name'];

    $otp = $_SESSION['otp'];




    // echo $e;
// echo $n;
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth = true;                                   // Enable SMTP authentication
    $mail->Username = 'gamegeniushub4@gmail.com';                     // SMTP username
    $mail->Password = 'cnvjsxfzfogtqgvd';                               // SMTP password
    $mail->SMTPSecure = 'ssl';
    ;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('gamegeniushub4@gmail.com', 'GAME GENIUS HUB');
    $mail->addAddress($e, $n);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Hello,' . $n . '!';
    $mail->Body = 'Your OTP for login is ' . $otp . '. THANK YOU';
    $mail->AltBody = 'THANK YOU';

    $mail->send();

    // $admin->redirect1('../Controller/updateorder.php?id='.$i);

    $admin->redirect1('../otp.php');
    //what you should do after sending mail

    exit();
} catch (Exception $e) {

    //error if somthing went wrong

    echo '<script>alert("Message could not be sent.")</script>';
}
?>