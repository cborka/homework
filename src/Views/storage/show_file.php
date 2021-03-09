<?php
global $mypdo;
//\System\Lib::var_dump($params);

// Фокус оказался в том, что php работает с файловой системой сервера, а html обращается к сайту
// и поэтому адрес картинки (src) указывается относительно корня сайта или текущего каталога.
// Поэтому копирую файл из хранилища в доступный каталог и уже затем показываю.
// А затем, после показа, удаляю.

echo $params['id'] . '<br>';

$sql =  <<< EOL
    SELECT c.id, c.user_id, u.login, u.name, c.file_name, c.file_token, c.load_date, c.file_size, c.access_rights 
      FROM storage_catalog c
        LEFT JOIN users u ON c.user_id = u.id 
      WHERE c.id = ?
EOL;
//echo $sql;

$rec = $mypdo->sql_one_record($sql, [$params['id']]);
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


echo 'Файл ' . $rec['file_name'] . '<br>';
echo 'Владелец ' . $rec['name'] . '<br>';
echo 'Загружен ' . $rec['load_date'] . '<br>';
echo 'Размер ' . $rec['file_size'] . '<br>';
echo 'Доступ: ' . ($rec['access_rights'] === '0' ? 'приватный' : 'публичный') . '<br><br><br>';

$filename = $rec['file_name'];
$fullname = $_SERVER['DOCUMENT_ROOT'] . "/storage/" . $filename;

if (!file_exists($fullname)) {
    $logger->debug('show_image: файл ' . $fullname . ' не существует.');
    return;
}

//$copy = $_SERVER['DOCUMENT_ROOT'] . "/public/storage/" . $filename;
//if (!copy($fullname, $copy)) {
//    $logger->debug('show_image: Не удалось скопировать файл ' . $filename);
//    return;
//}

//$extension = pathinfo($filename, PATHINFO_EXTENSION);

?>
<!--<div align="center" style="width: 100%;" onchange="setTimeout(delete_file, 3000)">-->
<!---->
<!---->
<!---->
<!--</div>-->

