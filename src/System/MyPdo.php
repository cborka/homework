<?php

namespace System;

class MyPdo
{
    private $dbh;

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
     *  Выполняет запрос возвращающий ОДНО значение
     */
    public function sql_one($sql, $params='')
    {
        global $logger;
        $logger->debug(self::class . '::sql_one()');

        $post = var_export($_POST, true);
        $logger->notice('post = ' . $post);


        $logger->notice('sql = ' . $sql);
        $logger->notice('params = ' . var_export($params, true));

         try {
            $statement = $this->dbh->prepare($sql);
            for ($i = 0; $i < count($params); $i++) {
                $statement->bindValue($i+1, $params[$i]); //, \PDO::PARAM_STR);
            }
            $statement->execute();
            $records = $statement->fetchAll();
        } catch (\PDOException $e) {
            $logger->error("MyPdo->sql_one: ($sql): {$e->getMessage()}");
        }

        // Если запрос ничего не вернул
        if( count($records) == 0 || count($records[0]) == 0) {
            return '';
        }
        return $records[0][0];
    }

}

//$mypdo = new MyPdo('mysql:host=93.189.42.2;dbname=myfs', 'boris', '54321');
//$pdo = $mypdo->getDbh();