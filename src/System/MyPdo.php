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
    public function __construct()
    {
        global $logger;

        // Получаю параметры подключения из файла
        try {
            $params = explode(',', file_get_contents ($_SERVER['DOCUMENT_ROOT'] . '/texts/db_connection.txt'));
        } catch (\Error $e) {
            $logger->error($e->getMessage());
            echo $e->getMessage();
            die();
        }

        try {
            $this->dbh = new \PDO($params[0], $params[1], $params[2],  [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
//            $logger->notice(self::class . " Подключился к БД $params[0] как $params[1]");
        } catch (\PDOException $e) {
            $logger->error(self::class . " Ошибка: " . $e->getMessage());
            echo self::class . " Ошибка: " . $e->getMessage() . "<br/>";
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

        $logger->debug('sql = ' . Lib::var_dump1($sql));
        $logger->debug('params = ' . Lib::var_dump1($params));

        try {
            $statement = $this->dbh->prepare($sql);
            $statement->execute($params);
        } catch (\PDOException $e) {
            $logger->error("MyPdo->sql_update: ($sql): \n {$e->getMessage()}");
            return 'PDOError';
        }

        // Количество строк затронутых оператором SQL
        // хотел проверять на успешность выполнения, но это оказалось лишним
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

        $logger->debug('sql = ' . $sql);
        $logger->debug('params = ' . Lib::var_dump1($params));

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
            $logger->debug('return = ;');
            return '';
        }

        $logger->debug('return = ' . $records[0][0]);
        return $records[0][0];
    }

    /*
     * Выполняет запрос возвращающий ОДНУ строку (запись)
     * первую, если вдруг запрос возвратил несколько строк
     * Возвращает ассоциативный массив
     */
    public function sql_one_record($sql, $params='')
    {
        global $logger;
        $logger->debug(self::class . '::sql_one_record()');

        $logger->debug('sql = ' . $sql);
        $logger->debug('params = ' . Lib::var_dump1($params));

        try {
            $statement = $this->dbh->prepare($sql);
            $statement->execute($params);
            $records = $statement->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $logger->error("MyPdo->sql_one: ($sql): \n {$e->getMessage()}");
            return 'PDOError';
        }

        // Если запрос ничего не вернул
        if( count($records) == 0 || count($records[0]) == 0) {
            $logger->debug('return = ; //запрос ничего не вернул');
            return '';
        }

        $logger->debug('return = ' . Lib::var_dump1($records[0]));
        return $records[0];
    }


    /*
     * Вставка в таблицу logs
     * отдельная функция, потому что тут не нужно записывать логи в логи
     */
    public function sql_insert_into_logs($sql, $params=[])
    {
        try {
            $statement = $this->dbh->prepare($sql);
            $statement->execute($params);
//        } catch (\Error $e) {     // Это не работает
//        } catch (\Exception $e) { // А это работает
        } catch (\PDOException $e) {
            echo $e->getMessage();
            die();
//            return 'PDOError';
        }

        return $statement->rowCount();
    }

}
