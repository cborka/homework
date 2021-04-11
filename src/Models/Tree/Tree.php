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
          ORDER BY path, name
EOL;
 //       WHERE path LIKE '/Тест/%'

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
            AND t.id != t.folder
          ORDER BY t.name
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
        if ($folder === '1') {
            $path = '/';
        } else {
            $rec = $mypdo->sql_one_record('SELECT name, path FROM tree WHERE id = ?', [$folder]);

            // path = folder_path + folder_name
            $path = $rec['path'] . $rec['name'] . '/';
        }

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
        global $dbh;

        $logger->debug(self::class . '::DeleteNode($node)');

        $node = substr($node, 1); // Находим node_id (просто убираем первый символ)

        // Ищем количество прямых потомков
        $child_num = $mypdo->sql_one('SELECT count(*) FROM tree WHERE folder = ?', [$node]);

        if ($child_num > 0) {
            return $child_num;
        }

        // Ищем папку (папка здесь звучит двусмысленно)
        $folder = $mypdo->sql_one('SELECT folder FROM tree WHERE id = ?', [$node]);

        try {
            $dbh->beginTransaction();

            $statement = $dbh->prepare('DELETE FROM tree WHERE id = ?');
            $statement->execute([$node]);

            $statement = $dbh->prepare('UPDATE tree SET ccount = ccount - 1 WHERE id = ?');
            $statement->execute([$folder]);

            $dbh->commit();
        } catch (\PDOException $e) {
            $dbh->rollBack();
            $logger->error("DeleteNode exception: \n {$e->getMessage()}");
            return 'PDOError';
        }

        return 0;
    }

    /*
     * Переименовать узел (пункт или папку)
     * вернуть OK в случае успеха
     */
    public static function RenameNode($node, $new_name)
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . '::RenameNode($node)');

        $node = substr($node, 1); // Находим node_id (просто убираем первый символ)


        // Информация о переименовываемом узле
        $rec = $mypdo->sql_one_record('SELECT flags, name, path FROM tree WHERE id = ?', [$node]);
        $old_node_path = $rec['path'];
        $old_full_name = $rec['path'] . $rec['name'] . '/';

        // Переименовать узел
        $count = $mypdo->sql_update('UPDATE tree SET name = ? WHERE id = ?', [$new_name, $node]);

        // Если это пункт, то есть потомков нету
        if ( $rec['flags'] & 1 === 1) {
            return 'OK';
        }

        // Обновить пути (path) у всех потомков

        // все потомки
        $recs = $mypdo->sql_many('SELECT id, path FROM tree WHERE path LIKE ?', [$old_full_name . '%']);
//        $recs = $mypdo->sql_many('SELECT id, path FROM tree WHERE path LIKE ?', ['%']);
        foreach ($recs as $rec) {
//            if ($old_node_path === '/') {
//                $old_node_path = '';
//            }
            $new_path =  $old_node_path . $new_name . '/' .  substr($rec['path'], strlen($old_full_name));
//
            $count = $mypdo->sql_update('UPDATE tree SET path = ? WHERE id = ?', [$new_path, $rec['id']]);

            // Первый вариант, оставлю на всякий случай
//            self::UpdatePath($rec['id']);
        }

        return 'OK';
    }

    /*
     * Обновить path у узла
     * Не используется, но оставлю, вдруг пригодится, здесь путь от потомка к самому первому предку
     */
    public static function UpdatePath($node)
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . "::UpdatePath($node)");


        $rec = $mypdo->sql_one_record('SELECT folder FROM tree WHERE id = ?', [$node]);
        $id = $rec['folder'];

        $path = '/';
        while ($id != '1') {
            $rec = $mypdo->sql_one_record('SELECT folder, name FROM tree WHERE id = ?', [$id]);
            $id = $rec['folder'];
            $path = '/' . $rec['name'] . $path;
        }

        $count = $mypdo->sql_update('UPDATE tree SET path = ? WHERE id = ?', [$path, $node]);

        $logger->debug(self::class . "::UpdatePath = $path");
    }


}