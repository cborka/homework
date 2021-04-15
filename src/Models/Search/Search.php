<?php

namespace Models\Search;

use System\MyPdo;
use System\Lib;
use System\Render;

/*
 *  Поиск по сайту
 */
class Search
{

    /*
     * Поиск и показ результатов поиска
     */
    public static function ShowSearchResults($search_text)
    {
        global $logger;
        global $mypdo;
        global $dbh;

        $logger->debug(self::class . "::ShowSearchResults($search_text)");

        $recs = $mypdo->sql_many('SELECT ref FROM search WHERE text LIKE ? order by ref', ['%'.$search_text.'%']);

        $result = '<h1>Поиск</h1>';
        $result .= ('Поиск ' . $search_text . '<br><hr>');
        foreach ($recs as $rec) {
            $ref = $rec['ref'];

            $result .= ($ref . '<br>');

        }

        Render::render($result);
    }

}