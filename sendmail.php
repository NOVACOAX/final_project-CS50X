<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if(isset($_POST["sendMail"])){
    if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
        $mail = new PHPMailer(true);

        $subject = $_POST["subject"] ." " . $_POST["name"];

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'hughherschell2018@gmail.com'; // Your gmail
        $mail->Password = 'stisfspbvwcmogbh'; // Your gmail app password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
    
        $mail->setFrom('hughherschell2018@gmail.com'); // Your gmail
    
        $mail->addAddress($_POST["email"]);
    
        $mail->isHTML(true);
    
        $mail->Subject = $subject;
        $mail->Body = $_POST["body"];
    
        $mail->send();
    
        $res = ['status' => 500];
            echo json_encode($res);
            return;
    }else {
        $_SESSION['message'] = "Error sending feedback!";
        return;
    }
}
?>