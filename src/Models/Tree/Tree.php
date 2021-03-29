<?php

namespace Models\Tree;

use mysql_xdevapi\Exception;
use PHPUnit\Framework\Error;
use System\Lib;
use System\MyPdo;
use System\Mailer;
use System\Render;
/*
*
*/
class Tree
{

    /*
     * Показать дерево
     */
    public static function Show()
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . '::Show()');

        $sql =  <<< EOL
        SELECT t.id, t.folder, t.list, t.flags, t.ccount, t.name, t.path
          FROM ((tree t
            LEFT JOIN tree f ON t.folder = f.id) 
            LEFT JOIN tree l ON t.list = l.id) 
          WHERE 1 = 1
          ORDER BY 1
EOL;
        //echo $sql;

        $recs = $mypdo->sql_many($sql);

        Render::render('','tree/show.php', ['recs' => $recs]);

    }

    /*
     * Добавить узел (пункт или папку) в указанную папку
     */
    public static function AppendNode($folder , $flags, $name)
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . '::AppendNode()');

        $folder = substr($folder, 1);

        // Информация о папке в которую всталяем новый узел
        $sql = 'SELECT name, path FROM tree WHERE id = ?';
        $rec = $mypdo->sql_one_record($sql, [$folder]);

        // path = folder_path + folder_name
        $path = $rec['path'] . $rec['name'] . '/';

        $rec = $mypdo->sql_update('INSERT INTO tree (folder, list, flags, ccount, name, path) VALUES (?, ?, ?, ?, ?, ?)', [$folder, $folder, $flags, 0, $name, $path]);
        $rec = $mypdo->sql_update('UPDATE tree SET ccount = ccount + 1 WHERE id = ?', [$folder]);



//        $dbh = $mypdo->getDbh();
//        try {
//            $dbh->beginTransaction();
//
//            // Пока что list = folder
//            $statement = $dbh->prepare('INSERT INTO tree (folder, list, flags, ccount, name, path) VALUES (?, ?, ?, ?, ?, ?)');
//            $statement->execute([$folder, $folder, $flags, 0, $name, $path]);
//
//            // Увеличить ccount у folder,
//            $statement = $dbh->prepare('UPDATE tree SET ccount = ccount + 1 WHERE id = ?');
//            $statement->execute([$folder]);
//
//        } catch (Exception $e) {
//
//            $dbh->rollBack();
//            $logger->error("AppendNode: \n {$e->getMessage()}");
//            return 'PDOError';
////            echo "Ошибка: " . $e->getMessage();
//        }

        // найти новое id
        $sql = 'SELECT id FROM tree WHERE name = ? AND folder = ?';
        $rec = $mypdo->sql_one($sql, [$name, $folder]);


//        $rec = $mypdo->sql_update($sql, [$folder, $folder, $flags, 0, $name, $path]);
//        Render::render('','tree/show.php', ['recs' => $recs]);
//        $id = 3;

        return $rec;
//        return Lib::var_dump1($rec);

    }





}