<?php
global $mypdo;
//\System\Lib::var_dump($params);

//
// Показ одной записи из дневника
//

echo $params['id'] . '<br>';

$rec = $mypdo->sql_one_record('SELECT id, dt, header, content FROM my_daily_news WHERE id = ?', [$params['id']]);

if (!$rec) // Нет такой записи в дневнике
{
    $msg = 'Error 404 Not Found - В дневнике не найдена запись ' . $params['id'];
    echo $msg;
    $logger->debug("mdnDayElement: $msg");
    return;
}

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
