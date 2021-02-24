<?php

namespace Models\MyDailyNews;

// use System\MyPdo;

/*
 *  Работа с таблицей БД my_daily_news
 */
class MdnDb
{
//   private $logger;
//
//    public function __construct()
//    {
//        global $logger;
//        $this->logger = $logger;
//
//        $this->logger->debug('__construct MdnDb пока не нужен.');
//    }

    //
    // Возвращает последние 10 записей
    //
    public static function getRecords ($where_clause = '')
    {
        global $dbh;
        global $logger;

        $logger->debug(self::class . '::getRecords()');

        if (!$_POST['search'] || $_POST['search'] == '') {
            $where = '';
        } else {
            $where = ' WHERE content LIKE \'%' . $_POST['search'] . '%\'';
        }

        $sql = 'SELECT * FROM my_daily_news ' . $where . ' ORDER BY dt DESC LIMIT 10';

//        $logger->debug('getRecords sql = ' . $sql);

        try {
            $statement = $dbh->prepare($sql);
            $statement->execute();
            $records = $statement->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return $records;
    }

    /*
     * Добавить/обновить запись в БД
     */
    public static function saveRecord($id, $dt, $header, $content)
    {
        global $dbh;
        global $logger;

        $logger->debug(self::class . '::saveRecords()');

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
            $statement = $dbh->prepare($sql);
            if ($id != 0) {
                $statement->bindParam(':id', $id);
            }
            $statement->bindParam(':dt', $dt);
            $statement->bindParam(':header', $header);
            $statement->bindParam(':content', $content);
            $count = $statement->execute();

            if ($count == 1) {
                return 0;
            } else {
                $logger->error("Запись в БД не удалась!!! sql = $sql");
                return 1;
            }
        } catch (\PDOException $e) {
            $logger->error($e->getMessage() );
            echo $e->getMessage();
        }
    }
}
