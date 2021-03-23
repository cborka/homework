<?php

namespace Models\Tree;

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





}