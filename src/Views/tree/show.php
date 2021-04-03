<?php
f_tree_show($params);

// Оборачиваю включаемые файлы в функции чтобы не было конфликтов переменных.
function f_tree_show($params)
{
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
        <button onclick="draw_folder('ul1', '')">DATA</button>

    </aside>

    <aside class="edit-element" id="element-id" oncontextmenu="show_tree_on_right_click('1', 'Тест');return false;">
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


        <div class="tree_box" id="tree-id" hidden></div>

    </aside>

    <span id="info3"></span>

<!--    <button onclick=" render_tree('1');">DATA</button>-->
<!--    <button onclick="show_folder('36', 'Cbcntvf36')"> BATON </button>-->

</div>

<script>

    // Показать полупрозрачный DIV, чтобы затенить страницу
    // (форма располагается не внутри него, а рядом, потому что она не должна быть полупрозрачной)
    function showCover() {
        let coverDiv = document.createElement('div');
        coverDiv.id = 'cover-div';

        // убираем возможность прокрутки страницы во время показа модального окна с формой
        document.body.style.overflowY = 'hidden';

        document.body.append(coverDiv);
    }
    function hideCover() {
        document.getElementById('cover-div').remove();
        document.body.style.overflowY = '';
    }

    //
    // Обработка результатов выбора узла из дерева
    //
    function on_ok (id)
    {
        document.getElementById('info2').innerHTML = id;
        hideCover();
        document.getElementById('tree_box').hidden = true;
        document.getElementById('tree-id').hidden = true;
        document.getElementById('tree_box').style.zIndex = -1;
        document.getElementById('tree-id').style.zIndex = -1;
    }

    //
    // Показ дерева по координатам мыши по правой кнопке мыши
    //
    function show_tree_on_right_click(id)
    {
        if (event.target.id !== "element-id") {
            return;
        }

        showCover();

        let menu = document.getElementById('tree-id');
        menu.style.position = "fixed";
        menu.style.display = 'block';

        // menu.onmouseleave = function () {
        //     event.target.style.display = 'none';
        // };

        // Располагаем меню по координатам мыши
        menu.style.left = event.clientX - 1 +'px';
        menu.style.top = event.clientY - 1 +'px';

        // menu.style.display = 'block';
        menu.hidden = false;

        show_folder(id, 'node_'+id);
    }

    //
    //  Дерево нарисовано, но пока скрыто
    //
    function render_tree(id)
    {
         $("#tree-id").html(
            ajax_render('tree/show_tree.php', [id])
        );

    }

    render_tree('1');

</script>

<?php } ?>