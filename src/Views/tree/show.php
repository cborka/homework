<div id="grid-id" class="grid-container-edit">

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
            <ul onclick="AppendLiX(this)">
                <li id="n1" onclick="InsertNode(this)">111</li>
                <li>222</li>
                <li>333</li>
                <ul>
                    <li>111</li>
                    <li>222</li>
                    <li>333</li>
                    <ul onclick="AppendLiX(this)">
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
                                <ul id="ul_id">
                                    <li>111</li>
                                    <li id="li_id" onclick="AppendLiP()">222</li>
                                    <li>333</li>
                                </ul>
                            </ul>
                        </ul>
                    </ul>
                </ul>
            </ul>

        </div>

<span id="info"></span>

<!--        <div class="popup_menu" id="pm0" onmouseleave="hide_pm2(this)" hidden>-->
        <div class="popup_menu" id="pm0" hidden>
            <button class="popup_menu_item" id="menu_item" onclick="alert(gen_tree(this))">K.y</button>
            <div class="popup_menu_item" id="qqq_menu_item" onclick="alert(this.id + ',' + this.parentNode.id)">Ky</div>
            <div class="popup_menu_item" id="q3_menu_item" onclick="AppendLi()">AppendLi()</div>
            <div class="popup_menu_item"><a href="#">Вход</a></div>
            <div class="popup_menu_item"><a href="#">Регистрация</a></div>
        </div>


        <div id="div1">
            div1
        </div>
        <div id="div2">
            div2
        </div>

        <?php
        echo
        '<div id="tmi0" class="top_menu_item" oncontextmenu="show_pm2(); return false;" >' .
            ' <span id="tmin0">Что?</span> ' .
        '</div> '
;


//onmouseleave = "hide_pm2('pm0')


 //var_dump($params['recs']) ?>


        <?php //var_dump($params['recs']) ?>
    </aside>

</div>

<script>

    //
    // Показать всплывающее меню
    //
    function show_pm2()
    {
        let element = event.target;                 // Элемент из которого вызываем меню
        let menu  =  document.getElementById('pm0');
        menu.style.display = 'block';
        menu.parent = element;

        // Располагаем меню по координатам мыши
        menu.style.left = event.clientX - 1 +'px';
        menu.style.top = event.clientY - 1 +'px';
        menu.onmouseleave = hide_pm2;

        return false; // чтобы не всплывало стандартное контекстное меню
    }

    //
    // Спрятать всплывающее меню
    //
    function hide_pm2(el)
    {
        event.target.style.display = 'none';
    }

    //
    // Добавление листочка-брата
    // Вызов из mi (из пункта всплывающего меню вызванного из листочка)
    //
    function AppendLi()
    {
        let mi = event.target;      // Пункт всплывающиего меню
        let pm = mi.parentElement;  // Всплывающее меню
        let li = pm.parent;         // Элемент li - лист дерева из которого вызвали всплывающее меню
        let ul = li.parentElement;  // Элемент ul - папка (ветка) дерева на котором растёт li

        // let ul = event.target.parentElement.parent.parentElement; // это ужасно

        // alert(event.target.id);
        // alert(menu.id);
        // alert(menu.parent.id);
        // alert(menu.parent.parentElement.id);

        let li_new = document.createElement('li');
        li_new.innerHTML = 'Я новый листочек';
//        li_new.id = "li_id";
        li_new.oncontextmenu = show_pm2;
//        li.onclick = event.target.onclick;
        ul.append(li_new);
    }

    //
    // Добавление листочка
    // Вызов (onclick) из елемента Li (листочка)
    //
    function AppendLiP()
    {
        let ul = event.target.parentElement;
        let li = document.createElement('li');
        li.innerHTML = 'prepend222';
        li.id = "li2_id";
//        li.onclick = AppendLiP;
        li.oncontextmenu = show_pm2;
//        li.onclick = event.target.onclick;
        ul.append(li);
    }



// ====================================================


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
    function o2s(obj)
    {
        var ret = 'x';
        for (let key in obj) {
            if (obj[key])
                ret +=  key + ' => ' + obj[key] + '<br>\n';
        }
        return ret;
    }

    // Генеалогическое древо елемента
    function gen_tree(el)
    {
        let elem = el;
        ret = elem.id + '/';
        while(elem = elem.parentNode) { // идти наверх до <html>
            ret += elem.id + '/';
        }
        return ret;
    }



</script>