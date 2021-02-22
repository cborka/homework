<?php

namespace System;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    public static function sendMail()
    {
        global $logger;
        $logger->debug(self::class .'::sendMail()');

         $page = <<<EOF
Привет, <h1> други </h1>друзья!
EOF;



        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.mail.ru';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'service23232';                       //SMTP username
            $mail->Password   = 'ljvfirf23232';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('service2323@mail.ru', 'Mailer');
            $mail->addAddress('cborka777@yandex.ru', 'Joe User');     //Add a recipient
            $mail->addAddress('cborka@mail.ru');               //Name is optional
 //           $mail->addReplyTo('cborka@mail.ru', 'Information');
//    $mail->addCC('cc@example.com');
//    $mail->addBCC('bcc@example.com');

            //Attachments
//    $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
//    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Такая тема';
            $mail->Body    = $page;
//            $mail->Body    = 'Это хтмл вобще то <b>жирняк!!!</b>';
//    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            $logger->debug(self::class .'::sendMail(): сообщение отправлено.');
        } catch (Exception $e) {
            $logger->debug(self::class ."::sendMail(): Ошибка ($mail->ErrorInfo): Сообщение не отправлено.");
    //        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }



}