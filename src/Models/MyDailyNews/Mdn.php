<?php

namespace Models\MyDailyNews;

use System\MyPdo;
use System\Lib;

/*
 *  Работа с таблицей БД my_daily_news
 */
class Mdn
{

    /*
     * Сохранить запись из дневника
     * вернуть id новой записи?
     */
    public static function saveRecord($id , $dt, $header, $content)
    {
        global $logger;
        global $mypdo;
        global $dbh;

        $logger->debug(self::class . "::saveRecord($id)");

        if ($id === '0') {
            $sql = 'INSERT INTO my_daily_news(dt, header, content) VALUES (?, ?, ?)';
        } else {
            $sql = <<< EOS
                UPDATE my_daily_news SET
                    dt = ?,
                    header = ?,
                    content = ?
                WHERE
                    id = $id 
EOS;
        }

        // Выясняем, есть ли соответствующая запись в табилце search
        // Здесь doc_type_id сначала надо бы найти, в другой базе данный он может оказаться другим
        $doc_type_id = 14;
        $count = '0';
        if ($id !== '0') {
            $count = $mypdo->sql_one('SELECT count(*) FROM search WHERE doc_type_id = ? AND doc_id = ?', [$doc_type_id, $id]);
        }

        if ($count === '0') {
            $sql2 = 'INSERT INTO search(ref, text, doc_type_id, doc_id) VALUES (?, ?, ?, ?)';
        } else {
            $sql2 = <<< EOS
                UPDATE search SET
                    ref = ?,
                    text = ?
                WHERE doc_type_id = ? 
                  AND doc_id = ?
EOS;
        }
        $logger->debug(self::class . "::sql2 = ($sql2)");

        $ref = 'Запись из дневника. <a href="/mdn/edit/' . $id . '" target="_blank"> ' . $header . '  </a><br>';
        $text = $dt . ' ' . date('l', strtotime($dt)) . ' ' . $header . ' ' . $content;

//        if (id === '0') {
//            id = sql_one('SELECT id FROM my_daily_news WHERE dt = ?', [dt]);
//            render_list();
//        }
//        render_element({id});

        try {
            $dbh->beginTransaction();

            // Вставляем/обновляем запись в дневнике
            $statement = $dbh->prepare($sql);
            $statement->execute([$dt, $header, $content]);

            // Вставляем/обновляем запись в таблицу поиска
            $statement2 = $dbh->prepare($sql2);
            $statement2->execute([$ref, $text, $doc_type_id, $id]);

            $dbh->commit();
        } catch (\PDOException $e) {
            $dbh->rollBack();
            $logger->error("saveMdnRecord exception: \n {$e->getMessage()}");
            return 'PDOError';
        }

        // найти новое id
        if ($id === '0') {
            $id = $mypdo->sql_one('SELECT id FROM my_daily_news WHERE dt = ? AND header = ?', [$dt, $header]);
        }
//        return $new_id;
        header("location: /mdn/edit/$id");
        return $id;
    }


    /*
     * Заполнить таблицу search
     */
    public static function fillSearch()
    {
        global $logger;
        global $mypdo;
        global $dbh;

        $logger->debug(self::class . "::fillSearch()");

        $recs = $mypdo->sql_many('SELECT id, dt, header, content FROM my_daily_news');

        foreach ($recs as $rec) {
            $id = $rec['id'];
            $dt = $rec['dt'];
            $header = $rec['header'];
            $content = $rec['content'];

            // Выясняем, есть ли соответствующая запись в табилце search
            // Здесь doc_type_id сначала надо бы найти, в другой базе данный он может оказаться другим
            $doc_type_id = 14;
            $count = '0';
            if ($id !== '0') {
                $count = $mypdo->sql_one('SELECT count(*) FROM search WHERE doc_type_id = ? AND doc_id = ?', [$doc_type_id, $id]);
            }

            if ($count === '0') {
                $sql2 = 'INSERT INTO search(ref, text, doc_type_id, doc_id) VALUES (?, ?, ?, ?)';
            } else {
                $sql2 = <<< EOS
                UPDATE search SET
                    ref = ?,
                    text = ?
                WHERE doc_type_id = ? 
                  AND doc_id = ?
EOS;
            }

            $ref = 'Запись из дневника. <a href="/mdn/edit/' . $id . '" target="_blank"> ' . $header . '  </a><br>';
            $text = $dt . ' ' . date('l', strtotime($dt)) . ' ' . $header . ' ' . $content;

            $result = $mypdo->sql_update($sql2, [$ref, $text, $doc_type_id, $id]);
            Lib::checkPDOError($result);
        }

    }

}
