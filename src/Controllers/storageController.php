<?php

namespace Controllers;

use Models\Storage\Storage;
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
     * Показать каталог загруженных файлов
     */
    public function actionCatalog()
    {
        $this->logger->debug(self::class . '->actionCatalog()');

        $id = $_SESSION['last_uploaded_id']?? '1';

        Render::render('','storage/catalog.php', ['id' => $id]);
    }

    /*
     * Загрузка файла, форма выбора файла
     */
    public function actionUpload_file()
    {
        global $logger;
        $logger->debug(self::class . '::actionUpload_file()');

        Render::render_file('storage/upload_file.php');
    }

    /*
     * Сохранение выбранного загруженного файла
     */
    public function actionSave_uploaded()
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . '::actionSave_uploaded()');

//        $file = $_FILES['filename'];

        Storage::Save_uploaded();
    }

    /*
     * Ajax-запрос на удаление временного файла
     */
    public function actionDelete_tmp()
    {
        global $logger;
        $logger->debug(self::class . '::actionDelete()');

        $file = $_POST['filename'];

        $copy = DOCUMENT_ROOT . "/public/storage/" . $file;

        if (!unlink($copy)) {
            $logger->warning("Файл $copy НЕ удален.");
        }
    }

    /*
     * Ajax-запрос на удаление файла из хранилища
     */
    public function actionDelete_from_storage()
    {
        global $mypdo;
        global $logger;
        $logger->debug(self::class . '::actionDelete()');

        $id = $_POST['id'];
        $filename = $_POST['filename'];

        // Находим реальное имя файла
        $token = $mypdo->sql_one('SELECT file_token FROM storage_catalog where id = ?', [$id]);
        Lib::checkPDOError($token);

        // Удаляем запись из базы данных
        $ret = $mypdo->sql_update('DELETE FROM storage_catalog where id = ?', [$id]);
        Lib::checkPDOError($ret);

        // Находим полное имя и удаляем файл
        $file = DOCUMENT_ROOT . "/storage/" . $token;
        $logger->debug("Удаляю $file");

        if (!unlink($file)) {
            $logger->debug("Файл $file НЕ удален.");
        }

        // Находим id последнего загруженного файла чтобы показать его
        $id = $mypdo->sql_one('SELECT MAX(id) FROM storage_catalog where user_id = ?', [$_SESSION['id']]);
        Lib::checkPDOError($id);

        $_SESSION['last_uploaded_id'] =  $id;

        echo $id;
    }

    /*
     * Ajax-запрос на скачивание файла
     */
    public function actionLoad()
    {
        global $logger;
        $logger->debug(self::class . '::actionLoad()');

        $token = $_GET['token'];
        $dirname = strtoupper(substr($token, 0, 1));

        $filename = $_GET['filename'];
        $logger->debug(self::class . ':: ' . $filename);

        $file = DOCUMENT_ROOT . "/storage/" . $dirname . '/' . $token;
//        $file = DOCUMENT_ROOT . "/storage/" . $token;

        if (!file_exists($file)) {
            echo 'Fайл <b>' . $filename . '</b> не существует.';
            $logger->debug("actionLoad: файл $file ($filename) не существует.");
            return;
        }

//        header('Content-Description: File Transfer');
//        header('Content-Type: application/octet-stream');
//        //header('Content-Disposition: attachment; filename=' . $filename);
//        header('Content-Disposition: attachment; filename=' . basename($file));
//        header('Content-Transfer-Encoding: binary');
//        header('Content-Length: ' . filesize($file));

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($filename));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        // читаем файл и отправляем его пользователю
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
     * Ajax-запрос. Обновить запись о файле в БД
     */
    public function actionUpdate_record()
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . '::actionUpdate_record()');

        return Storage::Update_record();
    }


}