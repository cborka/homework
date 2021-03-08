<?php

namespace Controllers;

use System\Render;
use System\Lib;


/*
 * Файловое хранилище
 */

class storageController
{

    /*
     * Загрузка файла
     */
    public static function actionUpload_file()
    {
        global $logger;
        $logger->debug(self::class . '::actionUpload_file()');

        Render::render_file('storage/upload_file.php');
    }

    /*
    * Сохранение загруженного файла
    */
    public static function actionSave_uploaded()
    {
        global $logger;
        $logger->debug(self::class . '::actionSave_uploaded()');

        $file = $_FILES['filename'];
        $filename = $_SERVER['DOCUMENT_ROOT'] . "/storage/" . $file['name'];
//        Lib::var_dump($file);

        move_uploaded_file($file['tmp_name'], $filename);

        $logger->debug("Файл  {$file['name']} загружен в хранилище.");

        $copy = $_SERVER['DOCUMENT_ROOT'] . "/public/storage/" . $file;

        Render::render_file("storage/show_image.php", ['file' => $file]);

        $logger->debug("Удаляю $copy");

        if (!unlink($copy)) {
            $logger->debug("Файл $copy НЕ удален.");
        }
    }

    public static function actionShow()
    {
        global $logger;
        $logger->debug(self::class . '::actionShow()');

//        $file = "Пагинация.jpg";
        $file = "zxcv.jpg";


        Render::render_file("storage/show_image.php", ['file' => $file]);
    }

    public static function actionDelete()
    {
        global $logger;
        $logger->debug(self::class . '::actionDelete()');

//        $file = "Пагинация.jpg";
        $file = $_POST['filename'];

        $copy = $_SERVER['DOCUMENT_ROOT'] . "/public/storage/" . $file;

        $logger->debug("Удаляю $copy");

        if (!unlink($copy)) {
            $logger->debug("Файл $copy НЕ удален.");
        }
    }

    public static function actionLoad()
    {
        global $logger;
        $logger->debug(self::class . '::actionLoad()');

        $file = $_SERVER['DOCUMENT_ROOT'] . "/storage/zxcv.jpg";
//        $file = $_SERVER['DOCUMENT_ROOT'] . "/publoc/favicon.ico";

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=123.jpg');
        //header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($file));

        readfile($file);
        exit();
    }

//    function file_force_download($file) {
//        if (file_exists($file)) {
//            header('X-Accel-Redirect: ' . $file);
//            header('Content-Type: application/octet-stream');
//            header('Content-Disposition: attachment; filename=' . basename($file));
//            exit;
//        }
//    }


}