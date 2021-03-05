<?php

namespace Controllers;

use Models\Users\RegUser;
use System\Render;
use System\Lib;
use System\Mailer;

/*
 * Работа с пользователями
 */
class usersController
{
    private $logger;

    public function __construct()
    {
        global $logger;
        $this->logger = $logger;
    }

    /*
     * Показать форму регистрации
     */
    public function actionShowRegForm()
    {
        $this->logger->debug(self::class . '->actionShowRegForm()');

        Render::render('',$_SERVER['DOCUMENT_ROOT'] . '/src/Views/users/reg.php');
    }

    /*
     * Зарегистрировать пользователя
     * сюда попадаем из формы регистрации, когда все поля заполнены и лежат в $_POST
     */
    public function actionReg()
    {
        $this->logger->debug(self::class . '->actionReg()');

        RegUser::regUser();
    }

    /*
     * Подтверждение почты пользователя
     */
    public function actionEmailConfirmation()
    {
        $this->logger->debug(self::class . '->actionConfirmation()');

        RegUser::emailConfirmation($_GET['token']);
    }

    /*
     * Показать форму входа
     */
    public function actionLogin()
    {
        $this->logger->debug(self::class . '->actionShowLoginForm()');

        Render::render('',$_SERVER['DOCUMENT_ROOT'] . '/src/Views/users/login.php');
    }

    /*
     * Войти на сайт
     * сюда попадаем из формы входа, когда все поля заполнены и лежат в $_POST
     */
    public function actionUserLogin()
    {
        $this->logger->debug(self::class . '->actionLogin()');

        RegUser::userLogin();
    }

    /*
     * Войти как Гость
     */
    public function actionLoginGuest()
    {
        $this->logger->debug(self::class . '->actionLoginGuest()');

        RegUser::userLoginGuest();
    }

    /*
     * Выход
     */
    public function actionLogout()
    {
        $this->logger->debug(self::class . '->actionLogout()');

        $_SESSION = [];
        Render::render('',$_SERVER['DOCUMENT_ROOT'] . '/src/Views/users/login.php');
    }

    /*
     * Домашняя страница
     */
    public function actionHome()
    {
        $this->logger->debug(self::class . '->actionHome()');

        if(isset($_SESSION['login'])) {
            if($_SESSION['login'] === 'Guest') {
                Render::render('', $_SERVER['DOCUMENT_ROOT'] . '/src/Views/users/home_guest.php');
            } else {
                Render::render('', $_SERVER['DOCUMENT_ROOT'] . '/src/Views/users/home.php');
            }
        } else {
            $_SESSION = [];
            Render::render('',$_SERVER['DOCUMENT_ROOT'] . '/src/Views/users/login.php');
        }
    }

    /*
     * Личный кабинет
     */
    public function actionAccount()
    {
        $this->logger->debug(self::class . '->actionAccount()');

        if(isset($_SESSION['login'])) {
            if($_SESSION['login'] === 'Guest') {
                Render::render('', $_SERVER['DOCUMENT_ROOT'] . '/src/Views/users/home_guest.php');
            } else {
                Render::render('', $_SERVER['DOCUMENT_ROOT'] . '/src/Views/users/account.php');
            }
        } else {
            $_SESSION = [];
            Render::render('',$_SERVER['DOCUMENT_ROOT'] . '/src/Views/users/login.php');
        }
    }

    /*
     * Сохранить данные акканунта пользователя
     * сюда попадаем из формы личного кабинета, когда все поля заполнены и лежат в $_POST
     */
    public function actionSaveAccount()
    {
        $this->logger->debug(self::class . '->actionSaveAccount()');

        RegUser::saveAccount();
    }

    /*
     * Изменить пароль
     * показать форму смены пароля
     */
    public function actionChangePassword()
    {
        $this->logger->debug(self::class . '->actionChangePassword()');

        if($_SESSION['login'] === 'Guest') {
            Render::render('', $_SERVER['DOCUMENT_ROOT'] . '/src/Views/users/home_guest.php');
        } else {
            Render::render('', $_SERVER['DOCUMENT_ROOT'] . '/src/Views/users/restore_pass2_get_pass.php', ['login' => $_SESSION['login']]);
        }
    }

    /*
     * Восстановлении пароля, шаг 0
     * показать форму ввода логина и почты для восстановления пароля
     */
    public function actionRestorePassword()
    {
        $this->logger->debug(self::class . '->actionRestorePassword()');
        $this->logger->debug('$_POST = '. Lib::var_dump1($_POST));

        Render::render('',$_SERVER['DOCUMENT_ROOT'] . '/src/Views/users/restore_pass1_get_mail.php');
    }

    /*
    * Восстановлении пароля, шаг 1
    */
    public function actionRestorePassword1()
    {
        $this->logger->debug(self::class . '->actionRestorePassword()');
        $this->logger->debug('$_POST = '. Lib::var_dump1($_POST));

        // Проверить, подтверждена ли почта
        // Добавить в БД хэш для восстановления пароля
        // Послать письмо со ссылкой на восстановление пароля
        // Сообщить о дальнейших действиях
        RegUser::restorePassword1();
    }

    /*
     * Восстановлении пароля, шаг 2
     * Ссылка сюда из письма
     */
    public function actionRestorePassword2()
    {
        $this->logger->debug(self::class . '->actionRestorePassword2');
        $this->logger->debug('$_POST = '. Lib::var_dump1($_POST));
        $this->logger->debug('$_GET = '. Lib::var_dump1($_GET));

        // Найти пользователя по токену
        // Показать форму ввода нового пароля
        RegUser::restorePassword2($_GET['token']);
    }

    /*
     * Восстановлении пароля, шаг 3
     */
    public function actionRestorePassword3()
    {
        $this->logger->debug(self::class . '->actionRestorePassword3');
        $this->logger->debug('$_POST = '. Lib::var_dump1($_POST));

        // Поменят пароль
        // Вывести сообщение с ссылкой на форму входа
        RegUser::restorePassword3();
    }


    // Тестил почту, оставлю для истории
//    public function actionSendMail()
//    {
//        $this->logger->debug(self::class . '->sendMail()');
//
//        Lib::var_dump($_POST);
//
//        Mailer::send('cborka@mail.ru',
//            'Tema',
//            'Привет от старых штиблет. Hi from old shoose.',
//            'Old fart');
//    }

}