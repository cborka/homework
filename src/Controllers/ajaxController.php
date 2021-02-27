<?php

namespace Controllers;

use System\Lib;
use System\MyPdo;

/*
 * Выполняет SQL-запросы и возвращает результат
 */
class ajaxController
{
    public static function actionSql_one()
    {
        global $mypdo;
        global $logger;

        $logger->debug(self::class . '::sql_one()');

        $sql = $_POST['sql'];
        $params = $_POST['params'];

        echo $mypdo->sql_one($sql, $params);
    }

}