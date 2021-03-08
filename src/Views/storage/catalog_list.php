<h3>Каталог загруженных файлов</h3>

<?php

global $mypdo;

//\System\Lib::var_dump($params);

$sql =  <<< EOL
    SELECT c.id, c.user_id, u.login, c.file_name, c.file_token, c.load_date, c.file_size, c.access_rights 
      FROM storage_catalog c
        LEFT JOIN users u ON c.user_id = u.id 
      ORDER BY login, file_name
EOL;
//echo $sql;

$recs = $mypdo->sql_many($sql);
//
//\System\Lib::var_dump($recs);
//\System\Lib::var_dump($_SESSION);


?>
<table id="list_table" border="1">
    <thead>
    <tr>
        <td>Пользователь</td>
        <td>Файл</td>
        <td>Загружен</td>
        <td>Размер</td>
        <td>0-личный</td>
<!--        <td>Токен</td>-->
    </tr>
    </thead>
    <?php foreach ($recs as $rec) { ?>
        <tr id="tr<?= $rec['id']; ?>" onclick="";>
            <td> <?= $rec['login']; ?></td>
            <td> <?= $rec['file_name']; ?></td>
            <td><?= substr($rec['load_date'], 0, 10); ?></td>
            <td align="right"> <?= $rec['file_size']; ?></td>
            <td> <?= $rec['access_rights']; ?></td>
<!--            <td> --><?//= $rec['file_token']; ?><!--</td>-->


<!--            <td style="white-space: normal;";>--><?//= $rec['header']; ?><!--</td>-->

            <!--А так более универсально, а если бы ещё знать здесь тип поля ...        -->
<!--                    --><?php //foreach ($rec as $fld) { ?>
<!--                        <td style="white-space: normal;">--><?//= $fld; ?><!--</td>-->
<!--                    --><?php //} ?>

        </tr>
    <?php } ?>


</table>

