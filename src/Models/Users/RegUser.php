<?php

namespace Models\Users;

use PHPUnit\Framework\Error;
use System\Lib;
use System\MyPdo;
use System\Mailer;
use System\Render;
use Models\Tree\Tree;
/*
 * Сначала класс был задуман для регистрации нового пользователя,
 * но сейчас здесь ещё восстановление пароля, вход, выход и личный кабинет
 */
class RegUser
{

    /*
     *  Регистрация нового пользователя
     */
    public static function regUser()
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . '::regUser()');

        // Отладочные сообщения
        $logger->debug('post = ' . Lib::var_dump1($_POST) );

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
        $message = file_get_contents(DOCUMENT_ROOT . '/src/Views/users/letter.html');
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
    public static function userLogin()
    {
        global $logger;

        $logger->debug(self::class . '::loginUser()');

        $flag = self::checkLogPass();

        if ($flag === '1' || $flag === '0') {
            // начать сессию, даже если почта не подтверждена

            self::sessionInit();

            Render::render('', DOCUMENT_ROOT . '/src/Views/users/home.php');

            return;
//        } else if ($flag === '0') {
//            // почта не подтверждена
//
//            Render::render("Ваша почта не подтверждена, регистрация не закончена. <br>
//            Подтвердите почту перейдя по ссылке в письме, которое было отправлено по вашему адресу. <br>
//            Если почта указана неверно, то можете поменять её в личном кабинете.");
//
//            return;
        }

        // Пользователь не найден
        $_SESSION = [];
        Render::render('', DOCUMENT_ROOT . '/src/Views/users/notfound.php');
    }

    /*
     * Login Guest
     */
    public static function userLoginGuest()
    {
        global $logger;

        $logger->debug(self::class . '::loginUserGuest()');

        // Обновляем переменные $_SESSION
        $_SESSION['id'] =    '13';
        $_SESSION['login'] =    'Guest';
        $_SESSION['email'] =    'guest@email';
        $_SESSION['name'] =     'Гость';
        $_SESSION['birthday'] = '01.01.2021';
        $_SESSION['phone'] =    '911';
        $_SESSION['flags'] =     '1';
        $_SESSION['notes'] =    '';

        Render::render('', DOCUMENT_ROOT . '/src/Views/users/home_guest.php');
    }

    /*
     * Сохранить данные акканунта пользователя
     * сюда попадаем из формы личного кабинета, когда все поля заполнены и лежат в $_POST
     */
    public static function saveAccount()
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . '::saveAccount()');

        $logger->notice('post = ' . Lib::var_dump1($_POST) );

        $login =    $_SESSION['login'];
        $email =    $_POST['email'];
        $name =     $_POST['name'];
        $birthday = $_POST['birthday'];
        $phone =    $_POST['phone'];
        $notes =    $_POST['notes'];

        $sql = <<< EOS
            UPDATE users SET  
                email = ?, 
                name = ?, 
                birthday = ?, 
                phone = ?, 
                notes = ? 
            WHERE login = ?
EOS;
        // Записываем данные в таблицу БД
        $result = $mypdo->sql_update($sql, [$email, $name, $birthday, $phone, $notes, $login]);
        Lib::checkPDOError($result);

        // Обновляем переменные $_SESSION
        $_SESSION['email'] =    $_POST['email'];
        $_SESSION['name'] =     $_POST['name'];
        $_SESSION['birthday'] = $_POST['birthday'];
        $_SESSION['phone'] =    $_POST['phone'];
        $_SESSION['notes'] =    $_POST['notes'];

        Render::render('', DOCUMENT_ROOT . '/src/Views/users/home.php');
    }

    /*
     * Проверить логин и пароль
     */
    public static function checkLogPass()
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . '::checkLogPass()');
        $logger->debug('post = ' . Lib::var_dump1($_POST) );

        $login = $_POST['login'];
        $password = md5($_POST['password']);

        $sql = 'SELECT flags & 1 FROM users WHERE login = ? AND password = ?';

        $flags = $mypdo->sql_one($sql, [$login, $password]);
        Lib::checkPDOError($flags);

        return $flags;
    }

    /*
     * Инициализация сессии (заполенние переменных $_SESSION данными пользователя
     */
    private static function sessionInit()
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . '::sessionInit()');

        $_SESSION = [];
        $_SESSION['login'] = $_POST['login'];

        $data = $mypdo->sql_one_record('SELECT id, name, email, phone, birthday, flags, created_at, notes FROM users WHERE login = ? ', [$_SESSION['login']]);
        Lib::checkPDOError($data);

        foreach ($data as $key => $value) {
            $_SESSION[$key] = $value;
        }

        // id папки в дереве, которая является корневой папкой в хранилище файлов для данного пользователя
        $_SESSION['fs_root'] = self::getFileStorageRoot();

        $logger->debug('$_SESSION = ' . Lib::var_dump1($_SESSION) );
    }

    /*
     *  Вернуть id папки в дереве, которая является корневой папкой в хранилище файлов для данного пользователя
     */
    private static function getFileStorageRoot()
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . '::sessionInit()');

        $login = $_SESSION['login'];

        $folder = $mypdo->sql_one('SELECT id FROM tree WHERE folder = 1 AND name = ?', ['Storage']);
        Lib::checkPDOError($folder);

        $sql = <<< EOS
            SELECT id 
              FROM tree
              WHERE name = ?
                AND folder = ?
EOS;
        $root_id = $mypdo->sql_one($sql, [$login, $folder]);
        Lib::checkPDOError($root_id);

        if ($root_id === '') {
            $root_id = Tree::AppendNode('f'.$folder , '2', $login);
        }

        return $root_id;
    }

    /*
     * Восстановлении пароля, шаг 1
     *   Проверить, подтверждена ли почта
     *   Добавить в БД хэш для восстановления пароля
     *   Послать письмо со ссылкой на восстановление пароля
     *   Сообщить пользователю о его дальнейших действиях (переходе по ссылке в письме)
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
            $logger->error('Почта не подтверждена. Что-то вообще не так пошло... respone = ' .  Lib::var_dump1($result));
            Render::render('Неизвестаная природе фатальная ошибка.');
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

        // Если запрос на смену пароля только по логину, получить email
        if ($email === '' ) {
            $email = $mypdo->sql_one('SELECT email FROM users WHERE login = ?', [$login]);
            // Здесь можно проверить адрес на правильность, но это вообще ...
        }

        $subject = 'Запрос на смену пароля';
        $message = file_get_contents(DOCUMENT_ROOT . '/src/Views/users/restore_pass_letter.html');
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
        Render::render('',DOCUMENT_ROOT . '/src/Views/users/restore_pass_msg.php');
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
        Render::render('',DOCUMENT_ROOT . '/src/Views/users/restore_pass2_get_pass.php', ['login' => $login]);
    }

    /*
     * Восстановлении пароля, шаг 3
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
        Render::render('',DOCUMENT_ROOT . '/src/Views/users/restore_pass3_ok.php');
    }

    /*
     * Свободен ли логин
     * Возвращает количество найденных логинов, 0 или 1
     */
    public static function isLoginFree($login)
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . "::isLoginFree($login)");

        return $mypdo->sql_one('SELECT count(*) FROM users WHERE login = ?', [$login]);
    }

    /*
     * Свободен ли email
     * Возвращает количество найденных email, 0 или 1
     */
    public static function isEmailFree($email)
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . "::isEmailFree($email)");

        return $mypdo->sql_one('SELECT count(*) FROM users WHERE email = ?', [$email]);
    }



    /*
     * Формирование меню, пока так
     */
    public static function getUserMenu ()
    {

        if (!isset($_SESSION['login'])) {
            $menu = <<<EOL
        &#9776;<a href="/users/login">Вход | </a>
        <a href="/users/showRegForm">&#10004;Регистрация | </a>
        <a href="/users/restorePassword">&#8634;Восстановление пароля ||| </a>
        <a href="/users/loginGuest">&#9749;Зайти как гость ||| </a>
        <a href="/mdn/edit">Дневник ||| </a>
EOL;
        } else if ($_SESSION['login'] === 'Guest') {
            $menu = <<<EOL
        <a href="/tree/show">&#9884;Дерево ||| </a>
        <a href="/mdn/edit">&#10001;Дневник.2 ||| </a>
        <a href="/mdn/view">Дневник.1 ||| </a>
        <a href="/storage/catalog">&#9733;Файлы ||| </a>
        <a href="/users/logout">Выход &raquo;| </a>
EOL;
        } else if ($_SESSION['login'] === 'nubasik13') {
            $menu = <<<EOL
        <a href="/tree/show">tree ||| </a>
        <a href="/storage/catalog">&#9733;storage ||| </a>
        <a href="/mdn/edit">&#9873;ajaxMdn ||| </a>
        <!--<a href="/help/ajaxRenderTest">ajaxRender ||| </a>-->
        <a href="/bulma.html">&#9773;bulma | </a>
        <a href="/bulma2.html">&#9773;bulma2 | </a>
        <a href="/bootstrap.html">bootstarp | </a>
        <a href="/flex.html">flex ||| </a>
        <a href="/help/test">Тесты ||| </a>
        <a href="/help/gitHelp">Git Help | </a>
        <a href="/help/jqueryHelp">jQuery Help | </a>
        <a href="/help/http">Http | </a>
        <a href="/help/CreationLog">CreationLog | </a>
        <a href="/help/jqueryLearn">jQuery Learn ||| </a>
        <a href="/help/docs">Описание ||| </a>
        <a href="/mdn/view">Дневник ||| </a>
        <a href="/users/logout">Выход &raquo;| </a>&#9809;
EOL;
        } else {
//            // И здесь хорошо бы проверить права
            $menu = <<<EOL
        <a href="/tree/show">&#9884;Дерево ||| </a>
        <a href="/help/docs">Описание ||| </a>
        <a href="/mdn/edit">&#9873;Дневник ||| </a>
        <a href="/storage/catalog">&#9733;Файлы ||| </a>
        <a href="/users/logout">Выход &raquo;| </a>
EOL;
        }

        return $menu;
    }

}