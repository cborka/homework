<?php

namespace Models\MyDailyNews;

// use System\MyPdo;

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
        $doc_id = '0';
        if ($id !== '0') {
            $doc_id = $mypdo->sql_one('SELECT count(*) FROM search WHERE doc_type_id = ? AND doc_id = ?', [$doc_type_id, $id]);
        }

        if ($doc_id === '0') {
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

        $ref = 'Запись из дневника. <a href="/mdn/edit/' . $doc_id . '">   </a>';
        $text = $header . ' ' . $content;

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
            $statement2->execute([$ref, $text, $doc_type_id, $doc_id]);

            $dbh->commit();
        } catch (\PDOException $e) {
            $dbh->rollBack();
            $logger->error("saveMdnRecord exception: \n {$e->getMessage()}");
            return 'PDOError';
        }

        // найти новое id
        $new_id = $mypdo->sql_one('SELECT id FROM my_daily_news WHERE dt = ? AND header = ?', [$dt, $header]);

        return $new_id;

//        location();
    }


}
