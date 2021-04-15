<?php
global $mypdo;

if ($params['id'] === '0') {
    $rec = ['id' => '0', 'dt' => '', 'header' => '', 'content' => ''];
} else {
    $sql = 'SELECT id, dt, header, content FROM my_daily_news WHERE id = ?';
    $rec = $mypdo->sql_one_record($sql, [$params['id']]);

    $rec['dt'] = date('Y-m-d\TH:i', strtotime($rec['dt']));
}
?>

<form name="edform" action="/mdn/save2" method="post">
    <input type="number" name="id" value="<?= $rec['id']; ?>" readonly hidden><br>
    <?= $rec['id']; ?><br><br>
    Дата и время<br>
    <input type="datetime-local" name="dt" value="<?= $rec['dt']; ?>" ><br><br>
    Тема<br>
    <input class="w100" type="text" name="header" value="<?= $rec['header']; ?>" ><br><br>
    Запись<br>
    <textarea class="w100" name="content" rows="20""><?= $rec['content']; ?></textarea>
    <br>
    Пароль<br>
    <input type="number" name="password" value="0"><br>
    <button>Save</button>
</form>

<!-- <button onclick="save_record(edform)">Save</button>-->
