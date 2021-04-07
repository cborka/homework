<?php
global $mypdo;
//\System\Lib::var_dump($params);

$sql = 'SELECT id, dt, header, content FROM my_daily_news WHERE id = ?';
//echo $sql;

$rec = $mypdo->sql_one_record($sql, [$params['id']]);
?>

<!-- Вывод на экран-->
<!--        --><?php //echo $rec['id']; ?><!--<br>-->
<?php

function dt_format($dt)
{
    $week_day = date('l', strtotime($dt));

    if ($week_day === 'Sunday') {
        $week_day = '<span class="red bold"> ' . $week_day . '</span>';
    }
    if ($week_day === 'Saturday') {
        $week_day = '<span class="green bold"> ' . $week_day . '</span>';
    }

    return $dt . ' ' . $week_day;
}

echo dt_format($rec['dt']);

// $week_day = date('l', strtotime($rec['dt']));
//
//if ($week_day === 'Sunday') {
//    $week_day = '<span class="red bold"> ' . $week_day . '</span>';
//}
//if ($week_day === 'Saturday') {
//    $week_day = '<span class="green bold"> ' . $week_day . '</span>';
//}
//
//echo $rec['dt'] . ' ' . $week_day;

?>

<h3><?php echo $rec['header']; ?></h3>

<p><?php echo str_replace("\n", '<br>', $rec['content']); ?></p>

<button onclick="render_element_edit({id: <?= $rec['id']; ?>})">Редактировать</button>
<br>
