<?php
//Adresse d'envoi des mails
$message  = 'Hi, my message!';

function sendMail($email, $message) {
    $to       = 'marathoncarti@gmail.com';
    $subject  = 'Email de ' . $email;

    $headers  = "From: {$email}" . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=utf-8';
    
    if(mail($to, $subject, $message, $headers))
        return true;
    else
        return false;
}
            