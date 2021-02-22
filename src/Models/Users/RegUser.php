<?php

namespace Models\Users;

use System\Lib;

class RegUser
{

    public static function regUser()
    {
        global $pdo;
        global $logger;

        $logger->debug(self::class . '::regUser()');

        Lib::var_dump($_POST);

        try {
            if (mail('cborka@mail.ru', 'Hi Subject', 'Hello, Mir!')) {
                $logger->debug(self::class . '::regUser() Почта отправлена!');
            }
            else {
                $logger->debug(self::class . '::regUser() Почта НЕ отправлена!');
            }
        } catch (\ERROR $e) {
            $logger->debug(self::class . '::regUser(): ' . $e->getMessage());
        }
//        mail (
//            string $to ,
//            string $subject ,
//            string $message ,
//            array|string $additional_headers = [] ,
//            string $additional_params = ""
//        ) : bool
        }


    }