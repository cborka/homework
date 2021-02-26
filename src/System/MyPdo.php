<?php

namespace System;

use System\Lib;
use System\Render;

class MyPdo
{
    private $dbh;

    /*
     * Подключение к БД
     */
    public function __construct($dsn, $user, $password)
    {
        try {
            $this->dbh = new \PDO($dsn, $user, $password,  [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
        } catch (\PDOException $e) {
            echo "MyPdo: Ошибка: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    /*
     *  Возвращает ссылку на подключение к БД
     */
    public function getDbh()
    {
        return $this->dbh;
    }


    /*
     *  Выполняет запрос НЕ возвращающий значений
     */
    public function sql_update($sql, $params='')
    {
        global $logger;
        $logger->debug(self::class . '::sql_update()');

        $logger->debug('sql = ' . $sql);
        $logger->debug('params = ' . Lib::var_dump1($params));

        try {
            $statement = $this->dbh->prepare($sql);
            $statement->execute($params);
        } catch (\PDOException $e) {
            $logger->error("MyPdo->sql_update: ($sql): \n {$e->getMessage()}");
            return 'PDOError';
        }

        // Количество строк затронутых оператором SQL
        // хотел проветять на успешность выполнения, но это оказалось лишним
        // потому что если что, например, нарушение целостности, вызывается исключение
        // Но пусть будет, пригодится для других целей

        return $statement->rowCount();
    }

    /*
     *  Выполняет запрос возвращающий ОДНО значение
     */
    public function sql_one($sql, $params='')
    {
        global $logger;
        $logger->debug(self::class . '::sql_one()');

//        $post = var_export($_POST, true);
//        $logger->notice('post = ' . $post);

        $logger->notice('sql = ' . $sql);
        $logger->notice('params = ' . Lib::var_dump1($params));

         try {
            $statement = $this->dbh->prepare($sql);
//  Этот код может пригодиться, пусть побудет пока, оказалось, что можно передавать массив прямо в execute
//            for ($i = 0; $i < count($params); $i++) {
//                $statement->bindValue($i+1, $params[$i]); //, \PDO::PARAM_STR);
//            }
            $statement->execute($params);
            $records = $statement->fetchAll();
        } catch (\PDOException $e) {
            $logger->error("MyPdo->sql_one: ($sql): \n {$e->getMessage()}");
            return 'PDOError';
        }

        // Если запрос ничего не вернул
        if( count($records) == 0 || count($records[0]) == 0) {
            $logger->notice('return = ;');
            return '';
        }

        $logger->notice('return = ' . $records[0][0]);
        return $records[0][0];
    }

}

//$mypdo = new MyPdo('mysql:host=93.189.42.2;dbname=myfs', 'boris', '54321');
//$pdo = $mypdo->getDbh();