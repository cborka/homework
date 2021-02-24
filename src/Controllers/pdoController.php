<?php

namespace Controllers;

use System\Lib;
use System\MyPdo;

/*
 * Выполняет SQL-запросы и возвращает результат
 */
class pdoController
{
    public static function actionSql_one()
    {
        global $mypdo;
        global $logger;

        $logger->debug(self::class . '::sql_one()');

        $sql = $_POST['sql'];

        echo $mypdo->sql_one($sql);
    }
}