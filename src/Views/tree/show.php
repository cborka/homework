<?php
f_tree_show($params);

// Оборачиваю включаемые файлы в функции чтобы не было конфликтов переменных.
function f_tree_show($params)
{

    if ($_SESSION['login'] === 'Admin13') {
        $tree_root_id = '1';
        $tree_root_name = '__/__';
    } else {
        $tree_root_id = '2';
        $tree_root_name = 'Test';
    }

    $recs = $params['recs'];
//    var_dump($recs);
?>


<div id="grid-id" class="grid-container-edit">

    <aside class="edit-list" id="list-id">
        Список
        <!-- Дерево в виде таблицы -->
        <table id="list_table" border="1">
            <thead>
            <tr>
                <td>id</td>
                <td>folder</td>
                <td>list</td>
                <td>flags</td>
                <td>ccount</td>
                <td>name</td>
                <td>path</td>
                <!--        t.id, t.folder, t.list, t.flags, t.ccount, t.name, t.path -->
            </tr>
            </thead>
            <?php
//            $records = json_decode($recs, true);
            foreach ($recs as $rec) { ?>
                <tr id="tr<?= $rec['id']; ?>">
                    <td align="id"> <?= $rec['id']; ?></td>
                    <td align="right"> <?= $rec['folder']; ?></td>
                    <td align="right"> <?= $rec['list']; ?></td>
                    <td align="right"> <?= $rec['flags']; ?></td>
                    <td align="right"> <?= $rec['ccount']; ?></td>
                    <td>
                        <?= $rec['name']; ?>
                    </td>
                    <td>
                        <?= $rec['path']; ?>
                    </td>
<!--                    <td>-->
<!--                        --><?//= $rec['path'] . $rec['name']; ?>
<!--                    </td>-->
                </tr>
            <?php } ?>
        </table>
<!--        <button onclick="draw_folder('ul1', '')">DATA</button>-->

    </aside>

    <aside class="edit-element" id="element-id" oncontextmenu="tree_show_on_click('<?= $tree_root_id; ?>', '<?= $tree_root_name; ?>', t_on_selection);return false;">
        <span id="info2" style="color: red;"></span><br>
        <h1>Тестовая веточка дерева</h1>
        Кликнуть правой кнопкой мыши в правой части экрана.<br><br>
        Свернуть-развернуть ветки - ПКМ (правая кнопка мыши), стрелки влево и вправо<br>
        Выбрать - ОК, или двойной клик или кнопка Выбрать.<br>
        Отмена - Esc или кнопка Отменить.<br>
        Переименовать - F2 или кнопка [~]<br>
        На верхних кнопках есть подсказки.<br>
        <br>
        Таблица слева обновляется при обновлении страницы, это как есть в базе данных.<br>
        <br>

    </aside>

    <div class="tree_box" id="tree-id" hidden></div>

</div>

<script>

    //
    // Обработка результатов выбора узла из дерева
    //
    function t_on_selection (id)
    {
        document.getElementById('info2').innerHTML = id;
    }

    // Начальная инициализация дерева
    render_tree('200');

</script>

<?php } ?>