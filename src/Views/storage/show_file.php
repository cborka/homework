<?php

use System\Render;

f123($params);

// Оборачиваю включаемые файлы в функции чтобы не было конфликтов переменных.
// Собирался целое сочинение писать на эту тему, а уложился в одну фразу.
function f123($params)
{
    global $mypdo;
    global $logger;
//\System\Lib::var_dump($params);

// Фокус оказался в том, что php работает с файловой системой сервера, а html обращается к сайту
// и поэтому адрес картинки (src) указывается относительно корня сайта или текущего каталога.
// Поэтому копирую файл из хранилища в доступный каталог и уже затем показываю.
// А затем, после показа, удаляю.
//    echo DOCUMENT_ROOT . '/src/Views/tree/show_tree.php<br>';
    echo $params['id'] . '<br>';

    $sql = <<< EOL
    SELECT c.id, c.user_id, u.login, 
           c.folder_id, CONCAT(t.path, t.name) AS folder, 
           u.name, c.file_name, c.file_token, c.load_date, c.file_size, c.access_rights, c.notes 
      FROM ((storage_catalog c
        LEFT JOIN users u ON c.user_id = u.id) 
        LEFT JOIN tree t ON c.folder_id = t.id) 
      WHERE c.id = ?
EOL;
//echo $sql;

    $rec = $mypdo->sql_one_record($sql, [$params['id']]);
    if (!$rec) // Нет такой записи в каталоге
    {
        $msg = '404 Not Found - В каталоге не найдена запись о файле №' . $params['id'];
        echo $msg;
        $logger->debug("show_image: $msg");
        return;
    }

//\System\Lib::var_dump($rec);

//array (size=9)
//  'id' => string '4' (length=1)
//  'user_id' => string '15' (length=2)
//  'login' => string 'nubasik13' (length=9)
//  'name' => string 'Нубасик13' (length=16)
//  'file_name' => string 'Солнце.png' (length=16)
//  'file_token' => string '30f8f93b9e00478989e639a8b0e68ef3b61d3fcb26008f2136daf6d919df2618' (length=64)
//  'load_date' => string '2021-03-09 12:30:34' (length=19)
//  'file_size' => string '72539' (length=5)
//  'access_rights' => string '0' (length=1)

    $id = $rec['id'];
    $is_owner = ($rec['user_id'] === $_SESSION['id']);

    if (!$is_owner && ($rec['access_rights'] === '0')) {
        echo "Это приватный файл, извините.";
        return;
    }

    if ($rec['access_rights'] === '0') {
        $selected0 = 'selected';
        $selected1 = '';
    } else {
        $selected0 = '';
        $selected1 = 'selected';
    }

    $filename = $rec['file_name'];
    $folder = $rec['folder'];
    $folder_id = $rec['folder_id'];

    $token = $rec['file_token'];
    $dirname = strtoupper(substr($token, 0, 1));
    $fullname = DOCUMENT_ROOT . "/storage/" . $dirname . '/' . $token;

    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $basename = pathinfo($filename, PATHINFO_FILENAME);

    if ($is_owner) { ?>
        <form name="rec_form" action="/storage/update_record" method="post">
            <input type="number" name="id" value="<?= $id; ?>" readonly hidden>
            <br>
            Файл    <input type="text" name="filename" value="<?= $basename; ?>">.<?= $extension; ?>
                    <input type="text" name="extension" value="<?= $extension; ?>" readonly hidden>
            <br>
            Каталог <input type="text" name="folder" value="<?= substr($folder, 1 + strlen('/Storage/'.$_SESSION['login'])); ?>" readonly>
                    <input type="text" name="folder_id" value="<?= $folder_id; ?>" readonly hidden>
<!--            (<span id="span_folder_id">--><?//= $folder_id; ?><!--</span>)-->
            <button type="button" onclick="tree_show_on_click('<?= $_SESSION['fs_root']; ?>', '__/__ ', t_on_change);return false;"> Изменить каталог </button>
            <br>
            Доступ
            <select size="1" name="access_rights">
                <option <?= $selected0; ?> value="0">Приватный</option>
                <option <?= $selected1; ?> value="1">Публичный</option>
            </select>
            <br>
            Описание<br><textarea name="notes" rows="2" cols="60"><?= $rec['notes'] ?></textarea><br>
            <button onclick="save_record()"> Сохранить изменения </button>
        </form>
        <button onclick="delete_from_storage('<?= $id; ?>', '<?= $filename; ?>')"> Удалить файл из хранилища </button><br><br>

    <?php } else {

        echo 'Файл: ' . $filename . '<br>';
        echo 'Каталог: ' . $folder . ' (' . $folder_id . ')<br>';
        echo 'Доступ: ' . ($rec['access_rights'] === '0' ? 'приватный' : 'публичный') . '<br>';
    }
    echo 'Владелец: ' . $rec['name'] . '<br>';
    echo 'Загружен: ' . $rec['load_date'] . '<br>';
    echo 'Размер: ' . $rec['file_size'] . '<br>';
//    echo '<pre>' . $rec['file_size'] . '</pre><br>';
    echo '<br><br>';

    if (!file_exists($fullname)) {
        echo 'Fайл <b>' . $filename . '</b> не существует.';
        $logger->debug("'show_image: файл $fullname ($filename) не существует.");
        return;
    }

    $copy = DOCUMENT_ROOT . "/public/storage/" . $filename;
    if (!copy($fullname, $copy)) {
        $logger->debug('show_image: Не удалось скопировать файл ' . $filename);
        return;
    }

//    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
?>

    <div align="center" style="width: 100%;">
        <?php switch ($extension) {
            case 'bat':
            case 'txt':
            case 'md':
                $content = Render::render_file_to_string($copy);
                ?>
                    <div align="left">
                        <p><?php echo $content; ?></p>
                    </div>
                <?php
                break;
            case 'xxx':
                ?>
                    <div align="left">
                        <?php include $copy; ?>
                    </div>
                    <?php
                break;
            case 'jpg':
            case 'png':
            case 'gif':
            case 'bmp':
                ?>
                    <img class="preview" src="<?= '/storage/' . $filename; ?>" alt="<?= $filename; ?>">
                <?php
                break;
        }
        ?>
    </div>
    <br><br>

    <a href="/storage/load?filename=<?= $filename; ?>&token=<?= $token; ?>">Скачать</a>
    <input id="ref" type="url" value="http://<?= $_SERVER['HTTP_HOST']; ?>/storage/load?filename=<?= $filename; ?>&token=<?= $token; ?>" readonly >
    <button onclick="copy_to()">Скопировать ссылку на скачивание в буфер обмена</button><br>
    <br>


<?php } ?>

