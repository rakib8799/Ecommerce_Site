<?php 
function send_mail($receiver, $subject, $body)
{
    // mail sending
    require 'phpmailer/PHPMailerAutoload.php';
    $mail = new PHPMailer;
    $sender_email = 'mkrakib007@gmail.com';
    $sender_pass = 'ugizrijymhaknfqh';
    
    // $receiver = $_SESSION['author_email'];
    // $mail->isSMTP(); // for localhost use enable this line otherwise don't use it
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 465;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    
    $mail->Username = $sender_email; // Sender Email Id
    $mail->Password = $sender_pass; // password of gmail
    
    $mail->setFrom($sender_email,'JKKNIU');
    
    $mail->addAddress($receiver); // Receiver Email Address
    $mail->addReplyTo($sender_email);
    
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $body;
    if($mail->send())
    {
        $mail->ClearAddresses();
        $mail->clearReplyTos();
        // mail_sent = 1 kore dilam er mane mail sent hoyse. 
    }
}
?>