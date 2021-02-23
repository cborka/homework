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
            echo "Ошибка: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function getDbh()
    {
        return $this->dbh;
    }
}

//$mypdo = new MyPdo('mysql:host=93.189.42.2;dbname=myfs', 'boris', '54321');
//$pdo = $mypdo->getDbh();