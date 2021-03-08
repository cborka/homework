<?php

namespace Controllers;

use System\Render;
use System\Lib;


/*
 * Файловое хранилище
 */

class storageController
{
    private $logger;

    public function __construct()
    {
        global $logger;
        $this->logger = $logger;
    }

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
        global $mypdo;

        $logger->debug(self::class . '::actionSave_uploaded()');

        $file = $_FILES['filename'];
        $fullname = $_SERVER['DOCUMENT_ROOT'] . "/storage/" . $file['name'];
//        Lib::var_dump($file);
//        array (size=5)
//  'name' => string 'Пагинация.jpg' (length=22)
//  'type' => string 'image/jpeg' (length=10)
//  'tmp_name' => string '/tmp/phpcgK0GN' (length=14)
//  'error' => int 0
//  'size' => int 68862
//return;

        $user_id = $_SESSION['id'];
        $file_name = $file['name'];
        $file_token = bin2hex(random_bytes(32));
//        $load_date = current_timestamp;
        $file_size = $file['size'];
        $file_type = $file['type'];
//        $access_rights = 0;

        $sql = 'INSERT INTO storage_catalog (user_id, file_name, file_type, file_token, file_size) VALUES (?, ?, ?, ?, ?)';

        // Записываем данные в таблицу БД
        $result = $mypdo->sql_update($sql, [$user_id, $file_name, $file_type, $file_token, $file_size]);
        Lib::checkPDOError($result);

        move_uploaded_file($file['tmp_name'], $fullname);

        $logger->debug("Файл  {$file['name']} загружен в хранилище.");

        Render::render_file("storage/show_image.php", ['file' => $file_name]);
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


    /*
     * Каталог загруженных файлов
     */
    public function actionCatalog()
    {
        $this->logger->debug(self::class . '->actionCatalog()');

        Render::render('','storage/catalog.php');
    }


}