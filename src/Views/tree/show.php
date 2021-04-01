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

    <aside class="edit-element" id="element-id">
        Элемент списка
    </aside>

<!--    <button onclick="draw_folder('f0')">DATA</button>-->

</div>

<script>

    render_test('1');

    function render_test(id)
    {
        $("#element-id").html(
            ajax_render('tree/show_tree.php', [id])
        );
    }

</script>

<?php } ?>