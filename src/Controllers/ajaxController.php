<?php

namespace Controllers;

use System\Lib;
use System\MyPdo;

/*
 * Здесь адреса для аякс-запросов
 */
class ajaxController
{
    /*
     * Выполняет SQL-запрос и возвращает результат
     */
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