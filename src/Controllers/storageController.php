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
     * Загрузка файла, форма выбора файла
     */
    public static function actionUpload_file()
    {
        global $logger;
        $logger->debug(self::class . '::actionUpload_file()');

        Render::render_file('storage/upload_file.php');
    }

    /*
    * Сохранение выбранного загруженного файла
    */
    public static function actionSave_uploaded()
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . '::actionSave_uploaded()');

        $file = $_FILES['filename'];

        $logger->debug(Lib::var_dump1($_FILES));

//        array (size=5)
//  'name' => string 'Пагинация.jpg' (length=22)
//  'type' => string 'image/jpeg' (length=10)
//  'tmp_name' => string '/tmp/phpcgK0GN' (length=14)
//  'error' => int 0
//  'size' => int 68862
//return;

        // Инициализация полей для вставки в таблицу-каталог
        // закомментированные поля вставятся по умолчанию
        $user_id = $_SESSION['id'];
        $file_name = $file['name'];
        $file_token = bin2hex(random_bytes(32));
//        $load_date = current_timestamp;
        $file_size = $file['size'];
        $file_type = $file['type'];
//        $access_rights = 0;

        $sql = 'INSERT INTO storage_catalog (user_id, file_name, file_type, file_token, file_size) VALUES (?, ?, ?, ?, ?)';

        $result = $mypdo->sql_update($sql, [$user_id, $file_name, $file_type, $file_token, $file_size]);
        Lib::checkPDOError($result);

        $fullname = $_SERVER['DOCUMENT_ROOT'] . "/storage/" . $file['name'];

        if (!move_uploaded_file($file['tmp_name'], $fullname)) {
            echo "Не удалось переместить";
            $logger->debug("Файл  {$file['name']} не удалось переместить в хранилище");
        } else {
            $logger->debug("Файл  {$file['name']} загружен в хранилище как $file_token");
        }

        $id = $mypdo->sql_one('SELECT id FROM storage_catalog where file_token = ?', [$file_token]);
        Lib::checkPDOError($result);

        $_SESSION['last_uploaded_id'] =  $id;

        header('location: /storage/catalog');
//        Render::render_file("storage/show_image.php", ['file' => $file_name]);
//        Render::render('','storage/catalog.php', ['id' => $id]);
    }

    public static function actionShow()
    {
        global $logger;
        $logger->debug(self::class . '::actionShow()');

        $file = "Пагинация.jpg";
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

        $token = $_GET['token'];
        $filename = $_GET['filename'];
//        $token = $_POST['token'];
//        $filename = $_POST['filename'];
        $logger->debug(self::class . ':: ' . $filename);

        $file = $_SERVER['DOCUMENT_ROOT'] . "/storage/" . $filename;

        if (!file_exists($file)) {
            echo 'Fайл <b>' . $file . '</b> не существует.';
            $logger->debug('actionLoad: файл ' . $file . ' не существует.');
            return;
        }


//        $file = $_SERVER['DOCUMENT_ROOT'] . "/public/favicon.ico";
        $logger->debug(self::class . ':: ' . $file);

//        header('Content-Description: File Transfer');
//        header('Content-Type: application/octet-stream');
//        //header('Content-Disposition: attachment; filename=' . $filename);
//        header('Content-Disposition: attachment; filename=' . basename($file));
//        header('Content-Transfer-Encoding: binary');
//        header('Content-Length: ' . filesize($file));


        // заставляем браузер показать окно сохранения файла
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
//        // читаем файл и отправляем его пользователю


        readfile($file);
//        exit();
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

        $id = $_SESSION['last_uploaded_id']?? '1';

        Render::render('','storage/catalog.php', ['id' => $id]);
    }


}