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


   
   $email=$_POST['ue'];
   $name=$_POST['un'];

    $a=$_POST['uid'];
    $b=$_POST['rid'];
   



 $target_dir="../Controller/uploads/";
  $image = $target_dir.basename($_FILES["pimage"]["name"]);
   move_uploaded_file($_FILES["pimage"]["tmp_name"],$image);

 


   


      $stmt=$admin->cud("INSERT INTO `prescription`(`uid`,`rid`,`image`)VALUES 
        ('".$a."','".$b."','".$image."')","saved");
  


  





    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'tilakarsikere3@gmail.com';                     // SMTP username
    $mail->Password   = 'A0987654321_';                               // SMTP password
    $mail->SMTPSecure = 'ssl';;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('tilakarsikere3@gmail.com', 'Ashirvad Medical Store');
    $mail->addAddress($email,$name);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'this is subject';
    $mail->Body    = 'Your uploaded prescribtion is ready please come and collect it from our store .';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();

 $admin->redirect1('../manager/viewpres.php');
     //what you should do after sending mail

    exit();
} catch (Exception $e) {
    
    //error if somthing went wrong

    echo '<script>alert("Message could not be sent.")</script>';
}
?>

