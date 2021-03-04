<?php

namespace Controllers;

use System\Lib;
use System\MyPdo;
use System\Render;

/*
 * Здесь адреса для аякс-запросов
 */
class ajaxController
{
    /*
     * Выполняет SQL-запрос и возвращает результат
     */
    public static function actionSql_one()
    {
        global $mypdo;
        global $logger;

        $logger->debug(self::class . '::actionSql_one()');

        $sql = $_POST['sql'];
        $params = $_POST['params'];

        echo $mypdo->sql_one($sql, $params);
    }

    /*
     * Возвращает Render указанного файла
     */
    public static function actionRender_file()
    {
        global $logger;

        $logger->debug(self::class . '::actionRender_file()');

        $filename = $_POST['filename'];
        $params = $_POST['params'];

//        echo Render::render_file_to_string($_SERVER['DOCUMENT_ROOT'] . '/public/jquery.html', ['yoyo']);
        echo Render::render_file_to_string($filename, ['00000', 'z' => 'yoyo']);
//        echo Render::render_file_to_string($filename, $params);

//        $v = Lib::var_dump1($_POST);
//        echo "fn = $filename, p = $params, POST = $v";
    }

}