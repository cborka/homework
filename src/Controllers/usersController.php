<?php

namespace Controllers;

use Models\Users\RegUser;
use System\Render;
use System\Lib;
use System\Mailer;

/*
 * Работа с пользователями
 *
 */
class usersController
{
    private $logger;

    public function __construct()
    {
        global $logger;
        $this->logger = $logger;
    }

    // Показать форму регистрации
    public function actionShowRegForm()
    {
        $this->logger->debug(self::class . '->actionShowRegForm()');

        Render::render('',$_SERVER['DOCUMENT_ROOT'] . '/src/Views/users/reg.php');
    }

    // Зарегистрировать пользователя
    // сюда попадаем из формы регистрации, когда все поля заполнены и лежат в $_POST
    public function actionReg()
    {
        $this->logger->debug(self::class . '->actionReg()');

        // Models\Users\RegUser
        RegUser::regUser();
    }






    //
    public function actionSendMail()
    {
        $this->logger->debug(self::class . '->sendMail()');

//        Lib::var_dump($_POST);
//
//        Mailer::send('cborka@mail.ru',
//            'Tema',
//            'Привет от старых штиблет. Hi from old shoose.',
//            'Old fart');
    }

    //
    public function actionCheck_login()
    {
        $this->logger->debug(self::class . '->actionCheck_login()');

//        //Lib::var_dump($_POST);
//        echo "login OK";

//        Render::render('',$_SERVER['DOCUMENT_ROOT'] . '/src/Views/users/reg.php');
    }


}