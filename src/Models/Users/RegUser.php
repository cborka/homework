<?php

namespace Models\Users;

use System\Lib;
use System\Mailer;

class RegUser
{

    public static function regUser()
    {
        global $pdo;
        global $logger;

        $logger->debug(self::class . '::regUser()');

//        Lib::var_dump($_POST);
//        array(6) {
//        ["login"]=>
//  string(9) "cborka777"
//        ["password"]=>
//  string(0) ""
//        ["password2"]=>
//  string(0) ""
//        ["test"]=>
//  string(3) "144"
//        ["fullname"]=>
//  string(12) "Михаил"
//        ["button_submit"]=>
//  string(0) ""
//}
        $email = $_POST['email'];
        $login = $_POST['login'];
        $name = $_POST['name'];
        $subject = 'Подтверждение почты. Email confirmation.';
        $message = <<<"EOF"
        
        Здравствуйте, $name

EOF;

        echo $message;
//        $email = $_POST['email'];

//        try {
//            Mailer::send('cborka@mail.ru', 'Hi Subject', 'Hello, Mir!');
//        } catch (\ERROR $e) {
//            $logger->debug(self::class . '::regUser(): ' . $e->getMessage());
//        }

        }


    }