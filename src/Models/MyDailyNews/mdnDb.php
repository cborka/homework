<?php

//require_once 'dbconnect.php';

use System\MyPdo;

//
// Добавить/обновить запись в БД
//
function addRecord($id, $dt, $header, $content)
{
    global $pdo;

    if ($id == 0) {
        $sql = 'INSERT INTO my_daily_news(dt, header, content) VALUES (:dt, :header, :content)';
    }
    else {
        $sql = <<< EOS
            UPDATE my_daily_news SET 
              dt = :dt,
              header = :header,
              content = :content
            WHERE
              id = :id  
EOS;
    }

    try {
        $statement = $pdo->prepare($sql);
        if ($id != 0) {
            $statement->bindParam(':id', $id);
        }
        $statement->bindParam(':dt', $dt);
        $statement->bindParam(':header', $header);
        $statement->bindParam(':content', $content);
        $count = $statement->execute();

        if ($count == 1) {
            return "Записано.<br>";
        } else {
            return "Не записано!!!<br>";
        }
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}

//
// Возвращает последние 10 записей
//
function getRecords ($where_clause = '')
{
    global $pdo;

    $where = ($where_clause != '') ? 'WHERE ' . escapeshellcmd($where_clause) : '';

    $sql = 'SELECT * FROM my_daily_news ' . $where . ' ORDER BY dt DESC LIMIT 10';

    $statement = $pdo->prepare($sql);
    $statement->execute();
    $records = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $records;
}