<?php
define('DIR','');
require_once DIR .'../config.php';
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

$ii=$_GET['idd'];

   
 
   $e= $_GET['email'];
   $n=$_GET['name'];


   
 $b='rejected';
$stmt=$admin -> cud("UPDATE `order` SET `ostatus`='$b' WHERE orid='$ii'","updated");

   


// echo $e;
// echo $n;
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'kapileshwarianiket04@gmail.com';                     // SMTP username
    $mail->Password   = 'cfsgjfpchmlkxjfd';                               // SMTP password
    $mail->SMTPSecure = 'ssl';;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('kapileshwarianiket04@gmail.com', 'KALPATARU MEDICALS ARSIKERE');
    $mail->addAddress($e, $n);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Hello,' .$n.'!';
    $mail->Body    = 'We are sorry to inform that your order has been cancelled and payment will be refundended .';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();

 $admin->redirect1('../admin/vieworder.php');
     //what you should do after sending mail

    exit();
} catch (Exception $e) {
    
    //error if somthing went wrong

    echo '<script>alert("Message could not be sent.")</script>';
}
?>
