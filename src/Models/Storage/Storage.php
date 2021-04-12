<?php

namespace Models\Storage;

//use mysql_xdevapi\Exception;
//use PHPUnit\Framework\Error;
use System\Lib;
use System\MyPdo;
use System\Mailer;
use System\Render;
/*
*
*/
class Storage
{

    /*
     * Сохранить загруженный (фактически) файл
     */
    public static function Save_uploaded()
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . '::Save_uploaded()');

        $file = $_FILES['filename'];

//        $logger->debug(Lib::var_dump1($_FILES));

//        array (size=5)
//  'name' => string 'Пагинация.jpg' (length=22)
//  'type' => string 'image/jpeg' (length=10)
//  'tmp_name' => string '/tmp/phpcgK0GN' (length=14)
//  'error' => int 0
//  'size' => int 68862
//return;

        // Инициализация полей для вставки в таблицу-каталог
        // закомментированные поля вставятся по умолчанию

        $folder_id = $_POST['folder_id'];

        $user_id = $_SESSION['id'];
        $file_name = $file['name'];
        $file_token = bin2hex(random_bytes(30)) . '.xbz';
//        $load_date = current_timestamp;
        $file_size = $file['size'];
        $file_type = $file['type'];
//        $access_rights = 0;

        $dirname = strtoupper(substr($file_token, 0, 1));

        // Полное имя каталога в хранилище
        $fulldirname = DOCUMENT_ROOT . "/storage/" . $dirname;

        if (!file_exists($fulldirname)) {
            if (!mkdir($fulldirname)) {
                $logger->error(self::class . "::Save_uploaded(): Не удалось создать каталог $fulldirname");
                echo "Не удалось создать каталог";
                return false;
            }
        }

        $fullname = $fulldirname . '/' .  $file_token;

        if (!move_uploaded_file($file['tmp_name'], $fullname)) {
            echo "Не удалось переместить";
            $logger->debug("Файл  {$file['name']} не удалось переместить в хранилище");
        } else {
            $logger->debug("Файл  {$file['name']} загружен в хранилище как $file_token");
        }


        $sql = 'INSERT INTO storage_catalog (user_id, folder_id, file_name, file_type, file_token, file_size) VALUES (?, ?, ?, ?, ?, ?)';

        $result = $mypdo->sql_update($sql, [$user_id, $folder_id, $file_name, $file_type, $file_token, $file_size]);
        Lib::checkPDOError($result);

        // Находим id загруженного файла чтобы показать его
        $id = $mypdo->sql_one('SELECT id FROM storage_catalog where file_token = ?', [$file_token]);
        Lib::checkPDOError($id);

        $_SESSION['last_uploaded_id'] =  $id;

        header('location: /storage/catalog');
//        Render::render_file("storage/show_image.php", ['file' => $file_name]);
//        Render::render('','storage/catalog.php', ['id' => $id]);
        return true;
    }

    /*
     * Обновить запись о файле в БД
     */
    public function Update_record()
    {
        global $logger;
        global $mypdo;

        $logger->debug(self::class . '::Update_record()');

        $filename = $_POST['filename'] . '.' . $_POST['extension'];
        $access_rights = $_POST['access_rights'];
        $notes = $_POST['notes'];
        $folder_id = $_POST['folder_id'];
        $id = $_POST['id'];


        $sql = 'UPDATE storage_catalog SET file_name = ?, access_rights = ?, folder_id = ? , notes = ?  WHERE id = ?';

        $result = $mypdo->sql_update($sql, [$filename, $access_rights, $folder_id, $notes, $id]);
        Lib::checkPDOError($result);

        $_SESSION['last_uploaded_id'] =  $id;

        header('location: /storage/catalog');
    }


}
