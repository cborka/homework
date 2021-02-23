<?php

namespace Controllers;

use System\Lib;
use PhpKit\ExtPDO\ExtPDO;

/*
 * Выполняет SQL-запросы и возвращает результат
 */
class pdoController
{
    public static function actionSql_one()
    {
        global $pdo;
        global $logger;

        // вынести код в system/MyPdo и чтобы он возвращал значение
        // а в Контроллере только интерфейс для запросов пользователя
        // и обозвать его АяксКонтроллер (но это видно будет)


        $logger->debug(self::class . '::sql_one()');

        $sql = $_POST['sql'];

        try {
            $statement = $pdo->prepare($sql);
            $statement->execute();
            $records = $statement->fetchAll();
        } catch (\PDOException $e) {
            echo 'PDOException: ' . $e->getMessage();
        }

//        Lib::var_dump($records);
//        echo $records;
        if( count($records) == 0 || count($records[0]) == 0) {
            echo '';
        }
        echo $records[0][0];

//        return $records;
    }
}