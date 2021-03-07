<h3>Дневник</h3>

<?php
//echo \System\Lib::var_dump1($_SESSION);
global $mypdo;

//\System\Lib::var_dump($params);

$sql = 'SELECT id, dt, header FROM my_daily_news ORDER BY dt DESC';
//echo $sql;

$recs = $mypdo->sql_many($sql);
//
//\System\Lib::var_dump($recs);

?>
<table id="list_table" border="1">
<tr>
    <td></td>
    <td align="right"><button onclick="add_record()">Новая запись</button></td>
</tr>
<?php foreach ($recs as $rec) { ?>
    <tr id="tr<?= $rec['id']; ?>" onclick="render_element({id: <?= $rec['id']; ?>})";
        ondblclick="render_element_edit({id: <?= $rec['id']; ?>})">

<!--Так можно настраивать параметры каждого поля-->
        <td><?= substr($rec['dt'], 0, 10); ?></td>
<!--        <td> --><?//= $rec['header']; ?><!--</td>-->
        <td style="white-space: normal;";><?= $rec['header']; ?></td>

<!--А так более универсально, а если бы ещё знать здесь тип поля ...        -->
<!--        --><?php //foreach ($rec as $fld) { ?>
<!--            <td style="white-space: normal;">--><?//= $fld; ?><!--</td>-->
<!--        --><?php //} ?>

    </tr>
<?php } ?>


</table>

