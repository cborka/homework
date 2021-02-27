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

    /*
     * Login
     */
    public static function loginUser()
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . '::loginUser()');

        $flag = self::checkLogPass();


        if ($flag === '1') {
            // начать сессию
            Render::render("Добро пожаловать!");

            return;
        } else if ($flag === '0') {
            // почта не подтверждена

            Render::render('Ваша почта не подтверждена, можете пока зайти как гость 
            или сначала подтвердить почту перейдя по ссылке в письме, которое было отправлено по вашему адресу
            или в личном кабинете поменять почту если она была указана неверно.');

            return;
        }

        // Польователь не найден
         Render::render('', $_SERVER['DOCUMENT_ROOT'] . '/src/Views/users/notfound.php');
    }

    public static function checkLogPass()
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . '::checkLogPass()');
        $logger->debug('post = ' . Lib::var_dump1($_POST) );

        $login = $_POST['login'];
        $password = md5($_POST['password']);

        $sql = 'SELECT flags | 1 FROM users WHERE login = ? AND password = ?';

        $flags = $mypdo->sql_one($sql, [$login, $password]);
        Lib::checkPDOError($flags);

        return $flags;
    }

    /*
     * Восстановлении пароля, шаг 1
     */
    public static function restorePassword1()
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . '::restorePassword1()');
        $logger->debug('$_POST = '. Lib::var_dump1($_POST));

        // Проверить, подтверждена ли почта, уже проверял на клиенте, но вдруг там хакер, тогда тут параноик

        $login = $_POST['login'];
        $email = $_POST['email'];

        if ($login !== '') {
            $result = $mypdo->sql_one('SELECT flags & 1 FROM users WHERE login = ?', [$login]);
        } else if ($email !== '' ) {
            $result = $mypdo->sql_one('SELECT flags & 1 FROM users WHERE email = ?', [$email]);
        } else {
            // ничего не ввели
            $logger->error('Клиент накосячил, почта и логин нулевые, так не может быть!');
            Render::render('Почта и логин пустые, так не может быть!');
            return;
        }

        if ($result === '0') {
            // Пользователь найден, почта не подтверждена
            $logger->error('Почта не подтверждена. Этого не может быть, так как проверка был на клиенте.');
            Render::render('К сожалению, ваша почта не подтверждена. <br>Смена пароля невозможна.');
            return;
        }

        if ($result !== '1') {
            $logger->error('Почта не подтверждена. Что-то вообще не так пошло... respone = ' .  Lib::var_dump1($respone));
            Render::render('Неизвестаня природе фатальная ошибка.');
            return;
        }


        // Добавить в БД токен для восстановления пароля

        $token = bin2hex(random_bytes(32));

        if ($login !== '') {
            $result = $mypdo->sql_update('UPDATE users SET token = ? WHERE login = ?', [$token, $login]);
        } else if ($email !== '' ) {
            $result = $mypdo->sql_update('UPDATE users SET token = ? WHERE email = ?', [$token, $email]);
        }

        if ($result !== 1) {
            // До этого места тоже теоретически не должны добраться
            $logger->error('Не смог записать токен в БД');
            Render::render('Не смог записать токен в БД');
            return;
        }

        // Послать письмо со ссылкой на восстановление пароля

        // Если запрос на смену пароля только по логину
        if ($email === '' ) {
            $email = $mypdo->sql_one('SELECT email FROM users WHERE login = ?', [$login]);
            // Здесь можно проверить адрес на правильность, но это вообще ...
        }

        $subject = 'Запрос на смену пароля';
        $message = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/src/Views/users/restore_pass_letter.html');
        $message = sprintf($message, $login, $token, $token, $token);

//       Render::render($message);
//       return;


        try {
            Mailer::send($email, $subject, $message);
        } catch (\ERROR $e) {
            $logger->error(self::class . '::restorePassword1(): ' . $e->getMessage());
            Render::render("Не смог отправить почту на $email, подробности в логах.");
        }

        $logger->notice("Отправлено письмо пользователю $login с токеном $token");

        // Сообщить о дальнейших действиях
        Render::render('',$_SERVER['DOCUMENT_ROOT'] . '/src/Views/users/restore_pass_msg.php');
    }

    /*
     * Восстановлении пароля, шаг 2
     */
    public static function restorePassword2($token)
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . '::restorePassword2()');
        $logger->debug('$_POST = '. Lib::var_dump1($_POST));

        // Найти пользователя по токену, здесь конечно будет полный перебор строк таблицы,
        // но ладно, не миллионы же пользователей у нас будут, да и пароль редко меняют
        $login = $mypdo->sql_one('SELECT login FROM users WHERE token = ?', [$token]);

        // Показать форму ввода нового пароля
        Render::render('',$_SERVER['DOCUMENT_ROOT'] . '/src/Views/users/restore_pass2_get_pass.php', ['login' => $login]);
    }

    /*
     * Восстановлении пароля, шаг 2
     */
    public static function restorePassword3()
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . '::restorePassword3()');
        $logger->debug('$_POST = '. Lib::var_dump1($_POST));

        // Поменять пароль
        $pass = md5($_POST['password']);
        $login = $_POST['login'];

        $result = $mypdo->sql_update('UPDATE users SET password = ?, token = ? WHERE login = ?', [$pass, '', $login]);

        // Вывести сообщение с ссылкой на форму входа
        Render::render('',$_SERVER['DOCUMENT_ROOT'] . '/src/Views/users/restore_pass3_ok.php');
    }


}