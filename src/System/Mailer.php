<?php

namespace System;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    public static function send($email, $subject, $message, $name = '' )
    {
        global $logger;
        $logger->debug(self::class .'::send()');

        $mail = new PHPMailer(true);

        // Перенаправил сообщения в свой логгер
        $mail->Debugoutput = function($str, $level) {
            global $logger;
            // Чтобы не выводил отладочные сообщения уровня 1 и 2
            if ($level > 2) {
                 echo "$str <br>";
                // $logger->debug(self::class .'::send()->$mail->Debugoutput: lvl=' . $level . ':' . $str);
                $logger->debug($level . ': ' . $str);
            }
        };
//       $mail->Debugoutput = $logger;

        $mail->CharSet = 'UTF-8';

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output

            $mail->isSMTP();
            $mail->Host = 'localhost';
            $mail->SMTPAuth = false;
            $mail->SMTPAutoTLS = false;
            $mail->Port = 25;

//            $mail->isSMTP();                                            //Send using SMTP
//            $mail->Host       = 'bc.eopa.ru';                     //Set the SMTP server to send through
//            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
//            $mail->Username   = 'bor';                          //SMTP username
//            $mail->Password   = '';                          //SMTP password

// Забанен
//            $mail->Host       = 'smtp.mail.ru';                         //Set the SMTP server to send through
//            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
//            $mail->Username   = 'service2323';                          //SMTP username
//            $mail->Password   = 'ljvfirf2323';                          //SMTP password
//
//            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
//            $mail->Port       = 25;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
//            $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('bor@bc.eopa.ru', 'HomeworkMailer');
            $mail->addAddress($email, $name);                           //Add a recipient
//          $mail->addAddress('cborka777@yandex.ru', 'Joe User');       //Add a recipient
//          $mail->addAddress('cborka@mail.ru');                        //Name is optional
//          $mail->addReplyTo('cborka@mail.ru', 'Information');
//          $mail->addCC('cc@example.com');
//          $mail->addBCC('bcc@example.com');
//
//          // Attachments
//          $mail->addAttachment('/var/tmp/file.tar.gz');               //Add attachments
//          $mail->addAttachment('/tmp/image.jpg', 'new.jpg');          //Optional name

            //Content
            $mail->isHTML(true);                                 //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;
//          $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            $logger->debug(self::class .'::send(): сообщение отправлено.');
            return true;
        } catch (Exception $e) {
            $logger->debug(self::class ."::send(): Ошибка ($mail->ErrorInfo): Сообщение не отправлено.");
//          echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    }
}