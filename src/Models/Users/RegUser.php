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
        $flags = 0;
        $token = bin2hex(random_bytes(32));

        $sql = 'INSERT INTO users(login, password, email, name, flags, token) VALUES (?, ?, ?, ?, ?, ?)';

        // Записываем данные в таблицу БД
        $result = $mypdo->sql_update($sql, [$login, $password, $email, $name, $flags, $token]);
        Lib::checkPDOError($result);

        // Отправляем письмо с просьбой подтвердить email
        $subject = 'Подтверждение почты. Email confirmation.';
        $message = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/src/Views/users/letter.html');
        $message = sprintf($message, $name, $token, $token, $token);

        try {
            Mailer::send($email, $subject, $message);
        } catch (\ERROR $e) {
            $logger->error(self::class . '::regUser(): ' . $e->getMessage());
            Render::render("Не смог отправить почту на $email, подробности в логах.");
        }

        $logger->notice("Отправлено письмо пользователю $login с токеном $token");
        Render::render(
            "Для подтверждения вашего адреса и завершения регистрации на ваш почтовый ящик отправлено письмо, <br>
                     перейдите по указанной в нём ссылке чтобы завершить регистрацию. <br>
                     Спасибо!"
        );

    }

    /*
     *  Проверка токена, подтверждение почты пользователя
     */
    public static function emailConfirmation($token)
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . '::EmailConfirmation()');

        $result =  $mypdo->sql_one('SELECT count(*) FROM users WHERE token = ? ', [$token]);
        Lib::checkPDOError($result);

        if ($result !== '1') {
            Render::render("Кажется ваша ссылка уже не работает.");
            return;
        }

        // Токен подтвержден, значит почта верная, устанавливаем соответствующий флажок
        $result =  $mypdo->sql_update("UPDATE users SET flags = flags | 1, token = '' WHERE token = ? ", [$token]);
        Lib::checkPDOError($result);

        Render::render("Адрес электронной почты подтверждён.");
    }

}