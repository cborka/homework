<?php
global $mypdo;
//\System\Lib::var_dump($params);

$sql = 'SELECT id, dt, header, content FROM my_daily_news WHERE id = ?';
//echo $sql;

$rec = $mypdo->sql_one_record($sql, [$params['id']]);
?>

<!-- Вывод на экран-->
<!--        --><?php //echo $rec['id']; ?><!--<br>-->
<?php echo $rec['dt'] . date(' l', strtotime($rec['dt'])); ?>

<h3><?php echo $rec['header']; ?></h3>

<p><?php echo str_replace("\n", '<br>', $rec['content']); ?></p>

<button onclick="render_element_edit({id: <?= $rec['id']; ?>})">Редактировать</button>
<br>
