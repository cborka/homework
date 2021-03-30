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
     * Показать таблицу дерева
     */
    public static function Show()
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . '::Show()');

        $sql =  <<< EOL
        SELECT id, folder, list, flags, ccount, name, path
          FROM tree
          ORDER BY 2, 1
EOL;

        $recs = $mypdo->sql_many($sql,[]);
        Render::render('','tree/show.php', ['recs' => $recs]);
    }

    /*
     * Вернуть веточку дерева
     */
    public static function GetFolder($folder)
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . "::GetFolder($folder)");

        $folder = substr($folder, 2); // Находим folder_id (просто убираем ul вначале)

        $sql =  <<< EOL
        SELECT t.id, t.folder, t.list, t.flags, t.ccount, t.name, t.path
          FROM ((tree t
            LEFT JOIN tree f ON t.folder = f.id) 
            LEFT JOIN tree l ON t.list = l.id) 
          WHERE t.folder = ? 
          ORDER BY 1
EOL;

        $recs = json_encode($mypdo->sql_many($sql, [$folder]));
        return $recs;
//        return $recs = '"' .json_encode($mypdo->sql_many($sql, [$folder])) . '"';
    }

    /*
     * Добавить узел (пункт или папку) в указанную папку
     * вернуть id нового узла
     */
    public static function AppendNode($folder , $flags, $name)
    {
        global $logger;
        global $mypdo;
        global $dbh;

        $logger->debug(self::class . '::AppendNode()');

        $folder = substr($folder, 1); // Находим folder_id (просто убираем первый символ)

        // Информация о папке в которую всталяем новый узел
        $rec = $mypdo->sql_one_record('SELECT name, path FROM tree WHERE id = ?', [$folder]);

        // path = folder_path + folder_name
        $path = $rec['path'] . $rec['name'] . '/';

        try {
            $dbh->beginTransaction();

            $statement = $dbh->prepare('INSERT INTO tree (folder, list, flags, ccount, name, path) VALUES (?, ?, ?, ?, ?, ?)');
            $statement->execute([$folder, $folder, $flags, 0, $name, $path]);

            $statement = $dbh->prepare('UPDATE tree SET ccount = ccount + 1 WHERE id = ?');
            $statement->execute([$folder]);

            $dbh->commit();
        } catch (\PDOException $e) {
            $dbh->rollBack();
            $logger->error("AppendNode exception: \n {$e->getMessage()}");
            return 'PDOError';
        }

        // найти новое id
        $new_id = $mypdo->sql_one('SELECT id FROM tree WHERE name = ? AND folder = ?', [$name, $folder]);

        return $new_id;
    }

    /*
     * Удалить узел (пункт или папку)
     * вернуть кол-во прямых потомков, если 0, то узел удалён
     */
    public static function DeleteNode($node)
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . '::DeleteNode($node)');

        $node = substr($node, 1); // Находим node_id (просто убираем первый символ)

        // Ищем количество прямых потомков
        $child_num = $mypdo->sql_one('SELECT count(*) FROM tree WHERE folder = ?', [$node]);

        if ($child_num > 0) {
            return $child_num;
        }

        $count = $mypdo->sql_update('DELETE FROM tree WHERE id = ?', [$node]);

        if ($count === 1) {
            return 0;
        }

        return -1; // До этого места вообще не должны дойти
    }

    /*
     * Переименовать узел (пункт или папку)
     * вернуть OK в случае успеха
     */
    public static function RenameNode($node, $name)
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . '::RenameNode($node)');

        $node = substr($node, 1); // Находим node_id (просто убираем первый символ)


        // Информация о переименовываемом узле
        $rec = $mypdo->sql_one_record('SELECT name, path FROM tree WHERE id = ?', [$node]);
        $path = $rec['path'] . $rec['name'] . '/';

        // Переименовать узел
//        $count = $mypdo->sql_update('UPDATE tree SET name = ? WHERE id = ?', [$name, $node]);

        // Обновить все пути (path) у узла и всех его потомков
        // ...
        $recs = $mypdo->sql_many('SELECT id FROM tree WHERE (id = ?) OR (path LIKE ?)', [$node, $path . '%']);

        $s = '=';
        foreach ($recs as $rec) {
//            update_path($rec['id']);
            $s .= $rec['id'] . ',';
        }
//        var_dump($recs);

return $s;
//return Lib::var_dump1($recs) . '>>>' . $s . '<<<';

//        if ($count === 1) {
//            return 'OK';
//        }

        return -1; // До этого места вообще не должны дойти
    }


}