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
    public function actionReg()
    {
        $this->logger->debug(self::class . '->actionReg()');

        RegUser::regUser();
    }


    // Зарегистрировать пользователя
    public function actionSendMail()
    {
        $this->logger->debug(self::class . '->sendMail()');

        Mailer::sendMail();
    }



}