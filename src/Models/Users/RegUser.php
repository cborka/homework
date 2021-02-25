<?php

namespace Models\Users;

use PHPUnit\Framework\Error;
use System\Lib;
use System\MyPdo;
use System\Mailer;
use System\Render;
/*
 *  Регистрация нового пользователя, вся информация в $_POST
 */
class RegUser
{

    public static function regUser()
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . '::regUser()');

        // Отладочные сообщения
        $logger->notice('post = ' . Lib::var_dump1($_POST) );

        $email = $_POST['email'];
        $login = $_POST['login'];
        $password = md5($_POST['password']);
        $name = $_POST['name'];
        $flags = 1;
        $token = bin2hex(random_bytes(32));

        $sql = 'INSERT INTO users(login, password, email, name, flags, token) VALUES (?, ?, ?, ?, ?, ?)';

        // Записываем данные в таблицу
//        try {
//            if($mypdo->sql_update($sql, [$login, $password, $email, $name, $flags, $token])) {
//                $return = 'Запись добавлена в БД. <br>';
//            } else {
//                Render::render('Непонятный облом, ошибка должна была остановить выполнение на прыдыдущем шаге.');
//            }
//        } catch (Error $e) {
//            Render::render('До этого места тоже дойти на должны ... ' . $e->getMessage());
//            exit;
//        }

        // Отправляем письмо с просьбой подтвердить email
        $subject = 'Подтверждение почты. Email confirmation.';
        $message = <<<"EOF"
        
 <table cellpadding="0" cellspacing="0" border="0"
       style="background-color: navy;
       border: 1px solid blueviolet;
       border-radius: 5px;">
    <tr>
        <td style="background-color: deepskyblue;
        padding: 5px 10px; border-bottom: 1px solid blue;
        border-top-left-radius: 4px; border-top-right-radius: 4px;
        font-family: 'Trebuchet MS', Helvetica, Arial, sans-serif; font-size: 11px; line-height: 1.231;">
            <a href="http://93.189.42.2/help/CreationLog" style="color: white; font-weight: bold; text-decoration:none">HomeWork</a>
        </td>
    </tr>
    <tr>
        <td style="background-color: white; padding: 1em;
        color: indigo;
    font-family: 'Trebuchet MS', Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.231;">
            <p style="margin-top: 0">
                Здравствуйте, $name!  <br>Для завершения регистрации на сайте
                "<a href="http://93.189.42.2/" style="color: blueviolet; text-decoration: none; font-weight: bold;">HomeWork</a>",
                необходимо подтвердить свой почтовый адрес, перейдя по ссылке ниже.
            </p>
            <h2 style="font-size: 14pt; font-weight: normal; margin: 10px 0 4px">
                <a href="http://93.189.42.2/users/confirmation?token=$token"
                   style="color: blueviolet; font-weight: bold; text-decoration: none">
                    Подтвердить учётную запись
                </a></h2>
            <div style="color: blueviolet; font-size: 11px; margin: 4px 0 10px">
                <a href="http://93.189.42.2/users/confirmation?token=$token"
                   style="color: blueviolet; text-decoration: none">
                    http://93.189.42.2/users/confirmation?token=$token</a>
            </div>
            Спасибо за регистрацию.<br/>HomeWork
        </td>
    </tr>
    <tr>
        <td style="background-color: lightseagreen; padding: 5px 10px;
        border-top: 1px solid green; border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px; text-align: right;
    font-family: 'Trebuchet MS', Helvetica, Arial, sans-serif; font-size: 11px;line-height: 1.231;">
            <a href="https://4gameforum.com/" style="color: white; font-weight: bold; text-decoration: none">http://93.189.42.2/help/CreationLog</a>
        </td>
    </tr>
</table>

EOF;

        $logger->notice('subject = ' . $subject);
        $logger->notice('$message = ' . $message);

//        $email = $_POST['email'];

//        try {
//            Mailer::send('cborka@mail.ru', 'Hi Subject', 'Hello, Mir!');
//        } catch (\ERROR $e) {
//            $logger->error(self::class . '::regUser(): ' . $e->getMessage());
//        }
        Render::render($message);

        }


    }