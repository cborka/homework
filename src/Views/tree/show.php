<div class="grid-container-edit">

    <aside class="edit-list" id="list-id">
        Список
    </aside>

    <aside class="edit-element" id="element-id">
        Элемент списка


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
            <?php foreach ($recs as $rec) { ?>
                <tr id="tr<?= $rec['id']; ?>">
                    <td align="id"> <?= $rec['id']; ?></td>
                    <td align="right"> <?= $rec['folder']; ?></td>
                    <td align="right"> <?= $rec['list']; ?></td>
                    <td align="right"> <?= $rec['flags']; ?></td>
                    <td align="right"> <?= $rec['ccount']; ?></td>
                    <td> <?= $rec['name']; ?></td>
                    <td> <?= $rec['path']; ?></td>
                </tr>
            <?php } ?>

        </table>

        <div>
            <span>Tree</span>
            <ul onclick="AppendLi(this)">
                <li id="n1" onclick="InsertNode(this)">111</li>
                <li>222</li>
                <li>333</li>
                <ul>
                    <li>111</li>
                    <li>222</li>
                    <li>333</li>
                    <ul onclick="AppendLi(this)">
                        <li>111</li>
                        <li id="lii2">222777</li>
                        <li>333</li>
                        <ul>
                            <li>111</li>
                            <li>222</li>
                            <li>333</li>
                            <ul>
                                <li>111</li>
                                <li>222</li>
                                <li>333</li>
                                <ul id="ulll">
                                    <li>111</li>
                                    <li id="lii" onclick="AppendLiP()">222</li>
                                    <li>333</li>
                                </ul>
                            </ul>
                        </ul>
                    </ul>
                </ul>
            </ul>


        </div>

<span id="info"></span>






        <?php //var_dump($params['recs']) ?>
    </aside>

</div>

<script>

    function AppendLiP()
    {
//        alert(ol.id);

        let ol = document.getElementById('lii');
        let el2 = document.getElementById('lii2');

        let li = document.createElement('li');
        li.innerHTML = el2.innerHTML; //'prepend222';
        ol.parentElement.append(li);

        el2.onclick = AppendLiP;
//        el2.onclick = ol.onclick;
//        ol.append(li);
    }

    function AppendLi(ol)
    {
        // let li = document.createElement('li');
        // let li2 = document.createElement('li');
        // li.innerHTML = 'prepend';
        // li2.innerHTML = 'append';
        // ol.prepend(li); // вставить liFirst в начало <ol>
        // ol.append(li2); // вставить liFirst в начало <ol>
    }

    function InsertNode(el)
    {
//        alert(el.id);

         $('#info').html(print_r(el));
//         $(#info).html(print_r(p));
    }


    function print_r(el) {
        var s = '';
        s += 'id = ' + el.id + '<br>';
        s += 'type = ' + el.type.type + '<br>';
        s += 'value = ' + el.value + '<br>';
        s += 'content = ' + el.innerHTML + '<br>';
        // for (let node of arr.childNodes) {
        //     alert(node);
        // }
        return s;
    }



    //var current_filename = '';
    //var result = '';
    //
    //render_list();
    //render_element({id: <?//= $params['id']; ?>//});
    //
    //// Подгоняю высоту элемента под высоту списка, которую жестко задаю в edit.css
    //$("#element-id").height($("#list-id").height()+17);
    //
    //// Рендер списка
    //function render_list(params = {})
    //{
    //    $("#list-id").html(
    //        ajax_render('storage/catalog_list.php', params)
    //    );
    //}


 </script>