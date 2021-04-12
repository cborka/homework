<div style="float: left;">
    <h3>Каталог загруженных файлов</h3>
</div>

<div style="float: right;">
    <?php if (isset($_SESSION['login']) && ($_SESSION['login'] !== 'Guest')) { ?>
        <button onclick="render_element_upload()" title="Загрузить новый">+<br>+<br>+</button>
    <?php } ?>
</div>

<br>

<?php

f_catalog_list($params);

// Оборачиваю включаемые файлы в функции чтобы не было конфликтов переменных.
function f_catalog_list($params)
{
    global $mypdo;

    //\System\Lib::var_dump($params);

    $sql =  <<< EOL
        SELECT c.id, c.user_id, u.login, c.folder_id, CONCAT(t.path, t.name) AS folder, c.file_name, c.file_token, c.load_date, c.file_size, c.access_rights 
          FROM ((storage_catalog c
            LEFT JOIN users u ON c.user_id = u.id) 
            LEFT JOIN tree t ON c.folder_id = t.id) 
          WHERE c.user_id = ?
             OR c.access_rights > 0 
          ORDER BY login, file_name
EOL;
  //echo $sql;

    $recs = $mypdo->sql_many($sql, [$_SESSION['id']]);

?>

    <table id="list_table" border="1">
        <thead>
        <tr>
            <td>Пользователь</td>
            <td>Папка</td>
            <td>Файл</td>
            <td>Загружен</td>
            <td>Размер</td>
            <td>0-личный</td>
    <!--        <td>Токен</td>-->
        </tr>
        </thead>
        <?php
        $prefix_lenght = 1 + strlen('/Storage/'.$_SESSION['login']);
        foreach ($recs as $rec) { ?>
            <tr id="tr<?= $rec['id']; ?>" onclick="render_element({id: <?= $rec['id']; ?>, fn: '<?= $rec['file_name']; ?>'})";>
                <td> <?= $rec['login']; ?></td>
                <td> <?= substr($rec['folder'], $prefix_lenght); ?></td>
<!--                <td> --><?//= $rec['folder_id']; ?><!--</td>-->
                <td> <?= $rec['file_name']; ?></td>
                <td><?= substr($rec['load_date'], 0, 10); ?></td>
                <td align="right"> <?= $rec['file_size']; ?></td>
                <td> <?= $rec['access_rights']; ?></td>
    <!--            <td> --><?//= $rec['file_token']; ?><!--</td>-->
            </tr>
        <?php } ?>


    </table>

<?php } ?>