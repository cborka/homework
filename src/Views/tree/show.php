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

        <div class="edit-element" id="tree-id">
        </div>



    </div>

    <span id="info2"></span>
<!--    <button onclick="draw_folder('f0')">DATA</button>-->
    <button onclick="show_folder('36', 'Cbcntvf36')">BATON</button>

</div>

<script>

    function on_xx (id) {
          alert('on_xx='+id);
    }
    function on_ok (id) {
//        alert('on_ok='+id);
        document.getElementById('tree_box').hidden = true;
    }

    function render_test(id, on_ok, on_xx)
    {
        $("#tree-id").html(
            ajax_render('tree/show_tree.php', [id])
        );
    }

    render_test('1', on_ok, on_xx);

//    alert('current = ' + tree_current_li.id);

</script>

<?php } ?>